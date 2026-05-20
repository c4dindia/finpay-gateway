<?php

namespace App\Http\Controllers;

use App\Models\SmilePay;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class SmilePayController extends Controller
{
    protected $baseURL = 'https://gateway.smilepayz.com';
    protected $merchantId = '21338';
    protected $merchantSecret = 'e1312b22774341ffd24384fd3f58c308099267c4c4350bb7b484113122f94b78';
    protected $merchantName = 'ryzen';
    protected $rsaPrivateKey = "-----BEGIN PRIVATE KEY-----
        MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC4+hyi/0nIg9wkL8GskUySqfEUvTYQ2Ir33kzzAOfzeUCPByu73ktBg/Wjlj+Cfh5d+xxZfiWAjbi25DlO59+tXmZI6yi9bPK/g2Nr2+CwihsLR4Wv4aD9N14+KQUy6WBd7YJHuRUmoxLCU8DtAtm7rjoqgit9cc9HZMmx8RRvbzVfxKJYqk5jfI8EeSvR9Ouofbtb+fSczudecd33YNd2/1OhES3zsFAY6Ryti6bDrtYpXI6VaZ8nm+xXejhMNAr14Dj1PCxUy6PpQ2F12SqkFShnsLhtch0DFeAwBnwLHZqMkmVlkF2DP2h4EvNix96fSS/slOsNMzhB5mXbsKX5AgMBAAECggEAHQOCDWD8ijqAg56IAtDRkWmdelLJUbAdcA6KxEzelPZuVWDbD78oz7BqjYuqD4ZHrhcOlczvBfB0yh8XDBTbpT5azdIw1DFsC/UIWakl35rx7f18bx91WHJgUmCtw5QOfP0dg7F4q1RFw3xOlP5u93y2++bp+mWpDPo2lrYJLgcfVHeG0goPyRcl03X7wtdZv3FxXiFfTMs5m1q3uKP9us+QECxHl2wp9IHYMPkF29haEUwMy1hXBEldgshDa5rUyfwG28K9g0ejf1y8U//qe8Et+aSCoAubOrOeF2amLxSaRRLuf0+438IjuwSCem5mhCnWtSeW//G3RUu2nuji7wKBgQDxqp+rKDrhgIvVYDZ6Vvx11oxWW/QTRBe6wL0q8pe3F2lB3aFLqn5MbAZwK0aG9E1VTulMIv+lCjif6xj9Zd9iZC22NcLGa+ZFdySN8p6ArErEd1Y/nASsmHaW0d4gs5KthzPwBIgTPNX4b+JaXObK9YmDbjNPF8AsneeHH9EKKwKBgQDD8rxDJQHdZg+rnjavHmy8B4gOO6MTXKmEVpJ3igdjt/NWd67lkXXRgIlZ2EU6YsgyZve/CA7xsbGZ/tVAoYPN5GuZxuSj25fviTznyn+GE72+9GnBnGOIcO09tllUChWVDk3yB5Wdh4XacU2pJlUBhNtutQm7WW7uVBEaEH8yawKBgQC9j/gk2q6tGhcVrZ2uAzOO/1cfmotu3Ve7vtnjqV0GWk/PQZj28GqXaZj3PYrZ6yIKC5cGiOF8xPK7xj7Y7aL229vbdHovCI4c6SL8UVaxWfRf/bfit3AXLpS/IyoY/qLMiSRdXkCaznqMoL6t4PeMNmPCe5u1q8yrcsUdAgEH9wKBgAgiruBWzAIGN8ZXVkvlBny0D1kP7tBh+4PMGF6wM2hXfdnsNC3B4VNmc81wJkerTEaTcdvTmNM+HnKXWwFI83D799+1m06WUnOWjhnNlYfaj/k6qp1oQdWrYp46UPElTgoXQvM2j2av9sXACIpqVgMvIfExwQqvRuTMRr1/S9irAoGASYMd8DXjCR3nRJbkRyTcMWJmk9tpixSDQ7jVlLi4fUBxPL80sLU0E6ec7boZdT/HK93oGg7DBqvC6I5ZG8czqb+IiMoYHUv1xSeOV167HNRYQTgB/CLaHs6B2w6ymU3T6SmsG5ToYih8j5k5aAGBs0e7slaeHMr/RxaNghU997g=
-----END PRIVATE KEY-----";

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = SmilePay::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $countries = collect([
            ['country' => 'Brazil', 'payment_method' => 'PIX', 'currency' => 'BRL'],
            ['country' => 'Mexico', 'payment_method' => 'SPEI', 'currency' => 'MXN'],
        ])->keyBy('currency');

        $validator = Validator::make($request->all(), [
            'amount'           => 'required|integer|min:10',
            'currency'         => 'required|string',
            'paymentMethod'    => 'required|string',
            'redirectUrl'  => 'nullable|url|max:255',
            'pixAccount' => 'nullable|string',
            'purpose' => 'required|string',
        ]);

        // Cross-field checks
        $validator->after(function ($validator) use ($countries, $request) {
            $currency = $request->input('currency');
            $method   = $request->input('paymentMethod');
            $country = $countries->get($currency);

            if (!$country) {
                $validator->errors()->add('currency', 'Unsupported currency.');
                return;
            }

            if ($request->filled('paymentMethod') && $method !== $country['payment_method']) {
                $validator->errors()->add('paymentMethod', 'The payment method does not match the selected currency.');
            }

            if ($currency === 'BRL' && $method === 'PIX') {
                if (!$request->filled('pixAccount')) {
                    $validator->errors()->add('pixAccount', 'pixAccount is required for BRL payments using PIX.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $timestamp = Carbon::now('Asia/Bangkok')->format('Y-m-d\TH:i:sP');
        $order = 'Order-' . strtoupper(uniqid());

        $body = [
            'orderNo'  => $order,
            'purpose'  => $request->input('purpose'),

            'merchant' => [
                'merchantId'      => $this->merchantId,
                'merchantName'    => $this->merchantName,
            ],

            'money' => [
                'currency' => $request->input('currency'),
                'amount'   => $request->input('amount'),
            ],

            'paymentMethod' => $request->input('paymentMethod'),
            'redirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'callbackUrl'   => 'https://payment.ryzen-pay.com/api/p15/notification',
        ];

        if ($request->filled('pixAccount')) {
            $body['payer'] = [
                'pixAccount' => $request->input('pixAccount'),
            ];
        }

        $minifiedBody = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($minifiedBody === false) {
            throw new \RuntimeException('JSON encode failed: ' . json_last_error_msg());
        }

        $stringToSign = $timestamp . '|' . $this->merchantSecret . '|' . $minifiedBody;

        $pkey = openssl_pkey_get_private($this->rsaPrivateKey);
        if (!$pkey) {
            throw new \RuntimeException('Invalid private key: ' . openssl_error_string());
        }

        $rawSignature = '';
        $ok = openssl_sign($stringToSign, $rawSignature, $pkey, OPENSSL_ALGO_SHA256);

        if (!$ok) {
            throw new \RuntimeException('Signing failed: ' . openssl_error_string());
        }

        $signatureBase64 = base64_encode($rawSignature);

        $client = new Client();

        try {
            $response = $client->post($this->baseURL . '/v2.0/transaction/pay-in', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-TIMESTAMP'  => $timestamp,
                    'X-SIGNATURE'  => $signatureBase64,
                    'X-PARTNER-ID' => $this->merchantId,
                ],
                'body' => $minifiedBody,
                'verify' => false
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $statusCode = $response->getStatusCode();

            Log::info("SmilePay payment request", [
                'payload' => $body,
            ]);

            if ($statusCode === 200) {
                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p15')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $request->input('currency');
                $trans->amount         = $request->input('amount');
                $trans->payment_id     = $body['tradeNo'];
                $trans->checkout_id    = $uuid;
                $trans->payment_status = 'Pending';
                $trans->description     = $order;
                $trans->status         = 'p15';

                $trans->save();

                $responseData = [
                    "success"     => true,
                    "amount"      => $request->input('amount'),
                    "currency"    => $request->input('currency'),
                    "checkout_id" => $uuid,
                    "link"        => $body['channel']['paymentUrl'],
                ];

                return response()->json($responseData, 200);
            }
        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode() ?? 400;
            $body   = (string) ($e->getResponse()?->getBody() ?? '');

            Log::error('Smile Payment Request Failed (ClientException)', [
                'status' => $status,
                'body'   => $body,
            ]);

            return response()->json([
                'error'  => 'Smile Payment Request Failed',
                'status' => $status,
                'body'   => $body,
            ], $status);
        } catch (Exception $e) {
            Log::error('Smile Payment Request Failed (Exception)', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => 'Smile Payment Request Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        Log::info('SmilePay webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('payment_id', $request->input('tradeNo'))->where('status', 'p15')->first() ?: new Transaction();
        $status = $request->input('status');

        $trans->currency       = $request->input('money.currency');
        $trans->amount         = $request->input('money.amount');
        $trans->payment_id      = $request->input('tradeNo');
        $trans->payment_status = ($status === 'SUCCESS') ? 'Completed' : ucfirst(strtolower($status));
        $trans->description    = $request->input('orderNo');
        $trans->status         = 'p15';

        $trans->save();

        $account = SmilePay::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p15/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("P15 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P15 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = SmilePay::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p15')->first();
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
