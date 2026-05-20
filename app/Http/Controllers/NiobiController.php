<?php

namespace App\Http\Controllers;

use App\Models\NiobiPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class NiobiController extends Controller
{
    protected $baseURL = 'https://users.niobi.co/api';

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = NiobiPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $countries = collect([
            ['id' => 1,  'country' => 'Kenya',         'country_code' => '254', 'currency' => 'KES'],
            ['id' => 2,  'country' => 'Benin',         'country_code' => '229', 'currency' => 'XOF'],
            ['id' => 3,  'country' => "Cote D'Ivoire", 'country_code' => '225', 'currency' => 'XOF'],
            ['id' => 4,  'country' => 'Cameroon',      'country_code' => '237', 'currency' => 'XAF'],
            ['id' => 9,  'country' => 'Senegal',       'country_code' => '221', 'currency' => 'XOF'],
            ['id' => 10, 'country' => 'Tanzania',      'country_code' => '255', 'currency' => 'TZS'],
            ['id' => 11, 'country' => 'Uganda',        'country_code' => '256', 'currency' => 'UGX'],
            ['id' => 12, 'country' => 'Zambia',        'country_code' => '260', 'currency' => 'ZMW'],
            ['id' => 13, 'country' => 'Sierra Leone',  'country_code' => '232', 'currency' => 'SLE'],
            ['id' => 16, 'country' => 'Ghana',         'country_code' => '233', 'currency' => 'GHS'],
            ['id' => 17, 'country' => 'Nigeria',       'country_code' => '234', 'currency' => 'NGN'],
        ])->keyBy('id');

        $validator = Validator::make($request->all(), [
            'first_name'       => 'required|string|min:2|max:100',
            'last_name'        => 'required|string|min:2|max:100',
            'email'            => 'required|email|max:150',
            'phone'            => 'required|string|min:6|max:20',
            'amount'           => 'required|string|min:1',
            'country_id'       => 'required|integer',
            'currency'         => 'required|string',
            'business_name'    => 'required|string|max:150',
            'item_name'        => 'required|string|max:150',
            'redirection_url'  => 'nullable|url|max:255',

            // logo as base64 (max 2MB after decode)
            'logo' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $maxBytes = 2 * 1024 * 1024; // 2MB
                    $base64 = $value;

                    if (preg_match('/^data:[^;]+;base64,/', $base64)) {
                        $base64 = substr($base64, strpos($base64, ',') + 1);
                    }

                    $base64 = preg_replace('/\s+/', '', $base64);
                    $decoded = base64_decode($base64, true);
                    if ($decoded === false) {
                        return $fail('Logo must be a valid base64 string.');
                    }

                    if (strlen($decoded) > $maxBytes) {
                        return $fail('Logo must be 2MB or smaller.');
                    }
                },
            ]
        ]);

        // Cross-field checks
        $validator->after(function ($validator) use ($countries, $request) {
            $countryId = (int) $request->input('country_id');

            if (!$countries->has($countryId)) {
                $validator->errors()->add('country_id', 'Selected country_id is invalid.');
                return;
            }

            $country = $countries->get($countryId);

            if ($request->filled('currency') && $request->input('currency') !== $country['currency']) {
                $validator->errors()->add('currency', 'Currency does not match the selected country.');
            }

            if ($request->filled('phone')) {
                $phone = preg_replace('/\D+/', '', $request->input('phone'));
                $code  = $country['country_code'];

                if (!Str::startsWith($phone, $code)) {
                    $validator->errors()->add('phone', "Phone must start with country code {$code}.");
                }
            }
        });

        $validated = $validator->validate();

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientId = $checkacc->clientId;
        $salt = bin2hex(random_bytes(16));
        $sender = 'Payment';

        $params = [
            'first_name'      => $validated['first_name'],
            'last_name'       => $validated['last_name'],
            'email'           => $validated['email'],
            'phone'           => preg_replace('/\D+/', '', $validated['phone']),
            'amount'          => $validated['amount'],
            'country_id'      => $validated['country_id'],
            'currency'        => $validated['currency'],
            'business_name'   => $validated['business_name'],
            'item_name'       => $validated['item_name'],
            'callback_url'    => 'https://payment.ryzen-pay.com/api/p14/notification',
            'redirection_url' => $validated['redirection_url'] ?? $checkacc->redirect_url,
            'logo'            => $validated['logo'],
        ];


        // create signature
        $client = new Client();

        try {
            $response = $client->request('POST', $this->baseURL . '/niobi-signature', [
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'client_id' => $clientId,
                    'sender'    => $sender,
                    'salt'      => $salt,
                    'params'    => $params,
                ],
                'verify' => false,
            ]);

            $body = json_decode($response->getBody(), true);
            $signature = $body['data']['signature'];
            $timestamp = $body['data']['timestamp'];

            Log::info('Niobi Signature Created', $body);
        } catch (RequestException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents(), true);

            Log::error('Niobi Signature Error', $error);
            return response()->json($error);
        }

        // create checkout
        try {
            $response = $client->request('POST', $this->baseURL . '/v3/payment-link-api/create', [
                'headers' => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'client_id' => $clientId,
                    'sender'    => $sender,
                    'salt'      => $salt,
                    'params'    => $params,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ],
                'verify' => false,
            ]);

            $body = json_decode($response->getBody(), true);

            if ($body['success'] == true) {
                Log::info('Niobi Checkout Created', $body);

                // store transaction
                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p14')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $validated['currency'];
                $trans->amount         = $validated['amount'];
                $trans->payment_id     = $body['data']['payment_link_id'];
                $trans->checkout_id    = $uuid;
                $trans->payment_status = 'Pending';
                $trans->description     = 'Payment is Initiated';
                $trans->status         = 'p14';

                $trans->save();

                Log::info("Transction Initialization Successful.");

                $responseData = [
                    "success"     => true,
                    "amount"      => $validated['amount'],
                    "currency"    => $validated['currency'],
                    "checkout_id" => $uuid,
                    "link"        => $body['data']['link'],
                ];

                return response()->json($responseData, 200);
            }
        } catch (RequestException $e) {
            $error = json_decode($e->getResponse()->getBody()->getContents(), true);

            Log::error('Niobi Checkout Error', $error);
            return response()->json($error);
        }
    }

    public function handleNotification(Request $request)
    {
        Log::info('Niobi webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('payment_id', $request->input('params.payment_link_id'))->where('status', 'p14')->first() ?: new Transaction();

        $trans->currency       = $request->input('params.currency');
        $trans->amount         = $request->input('params.amount');
        $trans->fees           = $request->input('params.fees');
        $trans->payment_id     = $request->input('params.payment_link_id');
        $trans->payment_status = ucfirst($request->input('params.status'));
        $trans->description    = rtrim($request->input('params.message'), " .") . ' with id: ' . $request->input('params.depositId');
        $trans->status         = 'p14';

        $trans->save();

        $account = NiobiPayment::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p14/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("P14 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P14 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = NiobiPayment::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p14')->first();
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
