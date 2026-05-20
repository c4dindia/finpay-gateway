<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TrustitBanking;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use Shawm11\Hawk\Client\Client as HawkClient;

class TrustitController extends Controller
{
    // protected $baseUrl = "https://api-sandbox.trustistecommerce.com";  //sandbox
    protected $baseUrl = "https://api.trustistecommerce.com";  //live

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = TrustitBanking::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'amount'    => 'required|numeric|min:0.1',
            'returnUrl' => 'nullable',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $client = new Client();
        $reference = 'REF-' . strtoupper(uniqid());
        $merchantPublicKey = $checkacc->merchant_public_key;
        $merchantSecretKey = $checkacc->merchant_secret_key;

        $endpointPath  = '/v1/payments';
        $fullUrlToSign = rtrim($this->baseUrl, '/') . $endpointPath;

        $payloadArr = [
            'amount'    => (float) $validated['amount'],
            'reference' => $reference,
            'returnUrl' => $validated['returnUrl'] ?? null,
        ];

        // Sign EXACT payload you will send
        $payloadJson = json_encode($payloadArr, JSON_UNESCAPED_SLASHES);

        $hawk = new HawkClient();

        $hawkOptions = [
            'credentials' => [
                'id'        => $merchantPublicKey,
                'key'       => $merchantSecretKey,
                'algorithm' => 'sha256',
            ],
            'ext' => null,
            'contentType' => 'application/json',
            'payload'     => $payloadJson,
        ];

        $hawkResult = $hawk->header($fullUrlToSign, 'POST', $hawkOptions);
        $hawkHeader = $hawkResult['header'];

        try {
            $response = $client->request('POST', $this->baseUrl . '/v1/payments', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $hawkHeader,
                    'Accept'        => 'application/json',
                ],
                'body' => $payloadJson,
                'verify' => false
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $paymentLink = $data['payLink'];

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p16')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $checkacc->merchant_public_key == 'c7a8a2b9-2c5e-4b2b-9c6b-4c7c9d5a0c3f' ? 'EUR' : 'GBP';
                $trans->amount         = $validated['amount'];
                $trans->checkout_id    = $uuid;
                $trans->payment_status = 'Pending';
                $trans->description     = $reference;
                $trans->status         = 'p16';

                $trans->save();

                Log::info("Transction Initialization Successful.");

                $responseData = [
                    "success"     => true,
                    "amount"      => $validated['amount'],
                    "currency"    => $checkacc->merchant_public_key == 'c7a8a2b9-2c5e-4b2b-9c6b-4c7c9d5a0c3f' ? 'EUR' : 'GBP',
                    "checkout_id" => $uuid,
                    "link"        => $paymentLink,
                ];

                return response()->json($responseData, 200);
            }
        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode() ?? 400;
            $body   = (string) ($e->getResponse()?->getBody() ?? '');

            Log::error('Trustist Payment Request Failed (ClientException)', [
                'status' => $status,
                'body'   => $body,
            ]);

            return response()->json([
                'error'  => 'Trustist Payment Request Failed',
                'status' => $status,
                'body'   => $body,
            ], $status);
        } catch (Exception $e) {
            Log::error('Trustist Payment Request Failed (Exception)', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => 'Trustist Payment Request Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        Log::info('Trustit webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('description', $request->input('reference'))->where('status', 'p16')->first() ?: new Transaction();

        $trans->currency       = $request->input('currency');
        $trans->amount         = $request->input('amount');
        $trans->payment_id     = $request->input('paymentId');
        $trans->payment_status = ucfirst(strtolower($request->input('status')));
        $trans->status         = 'p16';

        $trans->save();

        $account = TrustitBanking::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p16/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("P16 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P16 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = TrustitBanking::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p16')->first();
        if ($transaction == null) {
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'], 401);
        }

        return response()->json([
            'data' => [
                "currency" => $transaction->currency,
                "amount" => number_format($transaction->amount, 2),
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description,
                "created_at" => $transaction->created_at
            ]
        ], 200);
    }
}
