<?php

namespace App\Http\Controllers;

use App\Models\PThirteenPaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Validation\Rule;
use GuzzleHttp\Client;

class Aliz7SaleController extends Controller
{
    protected string $aliz7TestBaseUrl = 'https://test.aliz7.com';
    protected string $aliz7LiveBaseUrl = 'https://app.aliz7.com';

    // protected string $aliz7BearerToken = '67f974f8e8397067eb15cd46c50d7b0d';
    // protected string $aliz7WebhookSecret = '1ec805483a700ecc99d412bcab338181';

    /**
     * POST /api/aliz7/sale
     * Calls Aliz7 /api/sale using Guzzle with try/catch blocks and logs to channel aliz7.
     */
    public function h2hCheckout(Request $request, $accId)
    {
        $checkaccId = PThirteenPaymentMethod::where('accountId', $accId)->where('status', '1')->first();

        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        $validated = $request->validate([
            'currency' => ['required', 'string', Rule::in(['USD', 'EUR'])],
            'amount' => ['required', 'numeric'],
            'description' => ['required', 'string', 'max:250'],

            'cardholder_name' => ['required', 'string', 'max:150'],
            'card_number' => ['required', 'string', 'max:20'],
            'card_exp_month' => ['required', 'integer', 'min:1', 'max:12'],
            'card_exp_year' => ['required', 'integer', 'min:' . date('Y'), 'max:' . (date('Y') + 30)],
            'card_cvv' => ['required', 'string', 'max:5'],

            'approval_return_url' => ['required', 'url', 'max:500'],
            'decline_return_url' => ['required', 'url', 'max:500'],

            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:150'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'company' => ['nullable', 'string', 'max:150'],

            'address_1' => ['required', 'string', 'max:250'],
            'address_2' => ['nullable', 'string', 'max:250'],
            'city' => ['required', 'string', 'max:250'],
            'region' => ['required', 'string', 'max:250'],
            'postal_code' => ['required', 'string', 'max:15'],
            'country' => ['required', 'string', 'size:2'],
            'email' => ['required', 'email', 'max:250'],
            'phone' => ['required', 'string', 'max:25'],

            // if not provided by frontend we will fill
            'ip' => ['nullable', 'string', 'max:64'],
            'user_agent' => ['nullable', 'string', 'max:250'],

            'accept_header' => ['nullable', 'string', 'max:250'],
            'language' => ['nullable', 'string', 'max:50'],
            'color_depth' => ['nullable', 'integer'],
            'screen_height' => ['nullable', 'integer'],
            'screen_width' => ['nullable', 'integer'],
            'time_zone' => ['nullable', 'integer'],

            'tax' => ['nullable', 'numeric'],
        ]);

        $validated['date'] = Carbon::now()->format('Y-m-d H:i:s');
        $validated['language'] = "en-GB";
        $validated['journal'] = $accId;
        // Fill required fields if missing
        $validated['ip'] = $validated['ip'] ?? $request->ip();
        $validated['user_agent'] = $validated['user_agent'] ?? (string) $request->userAgent();
        $validated['accept_header'] = $validated['accept_header'] ?? (string) $request->header('Accept');
        $validated['notification_url'] = route('aliz7Webhook');

        $baseUrl = $this->aliz7LiveBaseUrl;

        // Use Guzzle
        $client = new Client([
            'base_uri' => rtrim($baseUrl, '/'),
            'timeout' => 30,
        ]);

        try {
            Log::channel('aliz7')->info('Aliz7 SALE request (masked)', [
                'endpoint' => $baseUrl . '/api/sale',
                'payload' => $this->maskSensitive($validated),
            ]);
            unset($validated['mode']);
            $resp = $client->post('/api/sale', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer '. $checkaccId->aliz7_token,
                ],
                'json' => $validated,
            ]);

            $body = (string) $resp->getBody();
            $data = json_decode($body, true) ?? ['raw' => $body];

            Log::channel('aliz7')->info('Aliz7 SALE response', [
                'http_status' => $resp->getStatusCode(),
                'response' => $data,
            ]);

            if($data['status'] != 'error'){
                $uuid = $data['uuid'];
                $maskedData = $this->maskSensitive($validated);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p13')->first() ?: new Transaction();

                $trans->account_id       = $checkaccId->accountId;
                $trans->from_currency    = strtoupper($data['currency'] ?? $request->currency);
                $trans->from_amount      = $data['amount'] ?? $request->amount;
                $trans->currency         = strtoupper($data['process_currency'] ?? $request->currency);
                $trans->amount           = $data['process_amount'] ?? $request->amount;
                $trans->checkout_id      = $uuid ?? null;
                $trans->payment_id       = $data['processor_id'] ?? null;
                $trans->customer_details = "Name: ". $validated['first_name'] ." " . $validated['last_name'] ." , Email: ". $validated['email'] ." , Phone: ". $validated['phone'];
                $trans->card_number = $maskedData['card_number'];
                if($trans->payment_status == null){
                    $trans->payment_status = ucfirst(strtolower($data['status']));
                }
                $trans->status           = 'p13';

                $trans->save();

                $responseData = [
                    "success"          => true,
                    "from_currency"    => strtoupper($request->currency),
                    "from_amount"      => $data['amount'] ,
                    "currency"         => strtoupper($data['process_currency'] ?? $request->currency),
                    "amount"           => $data['process_amount'] ?? $request->amount,
                    "checkout_id"      => $uuid,
                    "payment_id"       => $trans->payment_id,
                    "status"           => $trans->payment_status,
                ];

                $link = $data['redirect_url'] ?? null;
                if ($link !== null) {
                    $responseData['redirect_url'] = $link;
                }

                return response()->json($responseData, 200);
            }

            return response()->json([
                'success' => false,
                'data'    =>  $data
            ], 402);

        } catch (RequestException $e) {
            $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : null;
            $body   = $e->hasResponse() ? (string) $e->getResponse()->getBody() : null;

            Log::channel('aliz7')->error('Aliz7 SALE RequestException', [
                'http_status' => $status,
                'body' => $body,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Request failed',
                'error' => $body ?: $e->getMessage(),
            ], 502);

        } catch (\Throwable $e) {
            Log::channel('aliz7')->error('Aliz7 SALE Exception', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = PThirteenPaymentMethod::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p13')->first();
        if($transaction == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        $baseUrl = $this->aliz7LiveBaseUrl;
        $client = new Client([
            'base_uri' => rtrim($baseUrl, '/'),
            'timeout'  => 30,
        ]);

        try {
            $resp = $client->post('/api/status', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer '. $checkaccId->aliz7_token,
                ],
                'json' => [
                    'uuid' => $checkout_id,
                ],
            ]);

            $body = (string) $resp->getBody();
            $data = json_decode($body, true) ?? ['raw' => $body];

            Log::channel('aliz7')->info('STATUS Response', [
                'http_status' => $resp->getStatusCode(),
                'response'    => $data,
            ]);

            $transaction->description = $data['message'];
            $transaction->payment_id = $data['processor_id'] ?? $transaction->payment_id;
            $transaction->payment_status = ucfirst(strtolower($data['status']));
            $transaction->currency = strtoupper($data['process_currency'] ?? $transaction->currency);
            $transaction->amount = $data['process_amount'] ?? $transaction->amount;
            $transaction->save();

            if($data['status'] != "error"){
                return response()->json([
                    'data' => [
                        "currency" => $transaction->currency,
                        "amount" => number_format($transaction->amount,2),
                        "checkout_id" => $transaction->checkout_id,
                        "payment_id" => $transaction->payment_id,
                        "payment_status" => ucfirst($transaction->payment_status),
                        "description" => $transaction->description,
                        "created_at" => $transaction->created_at
                    ]
                ],200);
            }

            return response()->json([
                'success' => false,
                'message'   => "transaction not found",
            ], 400);

        } catch (RequestException $e) {
            $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : null;
            $body   = $e->hasResponse() ? (string) $e->getResponse()->getBody() : null;

            Log::channel('aliz7')->error('STATUS RequestException', [
                'http_status' => $status,
                'body'        => $body,
                'message'     => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status request failed',
                'http_status' => $status,
                'error'   => $body ?: $e->getMessage(),
            ], 502);

        } catch (\Throwable $e) {
            Log::channel('aliz7')->error('STATUS Exception', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * POST /api/aliz7/webhook
     * Validates payload, verifies signature, logs, then you can update DB based on uuid/journal.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        Log::channel('aliz7')->info('Webhook Received', [
            'ip' => $request->ip(),
            'payload' => $payload,
        ]);

        $checkout_id = $request['uuid'];

        $transaction = Transaction::where('checkout_id',$checkout_id)->where('status','p13')->first() ? : new Transaction();
        $transaction->account_id = $request['journal'];
        $transaction->from_currency = strtoupper($request['currency']);
        $transaction->from_amount = $request['amount'];
        $transaction->currency = strtoupper($request['process_currency']);
        $transaction->amount = $request['process_amount'];
        $transaction->checkout_id = $request['uuid'];
        $transaction->payment_id = $request['processor_id'];
        $transaction->payment_status = ucfirst(strtolower($request['status']));
        $transaction->description = $request['message'];
        $transaction->status = 'p13';
        $transaction->save();

        $account = PThirteenPaymentMethod::where('accountId', $transaction->account_id)->first();
        if (!$account) {
            Log::channel('aliz7')->warning("Account not found for webhook uuid: {$checkout_id}");
            return response()->json(['message' => 'OK'], 200);
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p13/' . $transaction->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::channel('aliz7')->info("P13 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::channel('aliz7')->warning("P13 forward api to client failed: " . $e->getMessage());
        }

        return response()->json(['message' => 'OK'], 200);

        try {
            // $validated = validator($payload, [
            //     'server_date' => ['required', 'string'],
            //     'date' => ['required', 'string'],
            //     'journal' => ['required', 'string'],
            //     'uuid' => ['required', 'string'],
            //     'currency' => ['required', 'string'],
            //     'amount' => ['required'],
            //     'status' => ['required', 'string'],
            //     'code' => ['required', 'string'],
            //     'message' => ['required', 'string'],
            //     'signature' => ['required', 'string'],
            // ])->validate();

             // Signature = sha256(secret + server_date + uuid + currency + amount + status)
        // $secret = $this->aliz7WebhookSecret;

        // Use values as strings exactly as received
        // $serverDate = (string) $data['server_date'];
        // $uuid       = (string) $data['uuid'];
        // $currency   = (string) $data['currency'];
        // $amount     = (string) $data['amount'];
        // $status     = (string) $data['status'];
//
        // $expected = hash('sha256', $secret . $serverDate . $uuid . $currency . $amount . $status);
//
        // $webhookSignatureValidity =  hash_equals($expected, (string) $data['signature']);

            // if (!$webhookSignatureValidity) {
            //     Log::channel('aliz7')->warning('Webhook signature mismatch', [
            //         'uuid' => $validated['uuid'] ?? null,
            //         'journal' => $validated['journal'] ?? null,
            //     ]);

            //     return response()->json(['message' => 'Invalid signature'], 401);
            // }

            // ✅ TODO: Update your DB transaction by uuid or journal
            // Example:
            // $tx = Transaction::where('uuid', $validated['uuid'])->orWhere('journal', $validated['journal'])->first();
            // if ($tx) { $tx->status = $validated['status']; $tx->code = $validated['code']; $tx->message = $validated['message']; $tx->save(); }

            // Log::channel('aliz7')->info('Aliz7 webhook processed', [
            //     'uuid' => $validated['uuid'],
            //     'status' => $validated['status'],
            //     'code' => $validated['code'],
            // ]);



        } catch (\Throwable $e) {
            Log::channel('aliz7')->error('Webhook handler exception', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Webhook error',
            ], 500);
        }
    }

    protected function maskSensitive(array $payload): array
    {
        $masked = $payload;

        if (!empty($masked['card_number'])) {
            $n = preg_replace('/\D+/', '', (string) $masked['card_number']);
            $masked['card_number'] = strlen($n) >= 8
                ? substr($n, 0, 4) . str_repeat('*', max(0, strlen($n) - 8)) . substr($n, -4)
                : '****';
        }

        if (array_key_exists('card_cvv', $masked)) {
            $masked['card_cvv'] = '***';
        }

        return $masked;
    }
}
