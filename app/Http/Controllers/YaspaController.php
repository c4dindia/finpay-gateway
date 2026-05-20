<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\YaspaBanking;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class YaspaController extends Controller
{
    protected $baseURL = 'https://testapi.yaspa.com';
    protected $apiKey = '2d2207bb-547c-45f3-8c79-611d8fe36ea4';
    protected $rsaSecret = "-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCvpnT25Ufq2d/b
GA9/Gp8K7DzRuuOfEY7hbmG/msosfyneir8Vtbmel//Jm0kR8b3BrvsIif8psa2V
dEvNcloVWqQHYNNbQrd0MJBtdf/fcpgW8bJCU6Tkb3OeQuDgULwZ5CmO4vwHfScL
EuIRpMWRzXqIvDQYv8420uF6OxcmWRst4+d1DBZc1x6JID+trFRH+gjwcstjEwBo
QXooEjWcrdpS2VrU8w3YKvgLrAe1rkFlD9ySHm8rrmvUetdzonnJxfJ+G9Us8m8X
OYTn6cfrOpwozh+9DC9xUm5lmPTe5mOu0u65OEyu9tfwMfBkDl6EstYvKj3qt3vr
LK0tL85BAgMBAAECggEAFNI74vD0nJaZUjK40omssOomPvvDA+38RIV1xZVTEiF8
THagtlF5eH7e2a6EQ8HmyxzjgcR+6Fm5IIJdwnOj0UxybXP5NeQTlEy18B3I2BtQ
w0aBCZr57MC34TqfBsQXOg/5a6hFNRuNK2NAhFQmfdUwSWFa4RSqfIX0p601viwn
A/OuCtKhEmlk3pduVmfhQnouPjUT0wTsZqo6rjM/j18f4f8Y4rg5A93kyu1tlfft
qFAenNT9qZmMZkOXw/egmnkDaYu6kweUkN1xefJPGbyWMzvIiBX8MgNFlh7H/YpT
kEVADDJu6s6BonM/muyczGTy2xX1yTar9tl8JQD5WQKBgQDtbgeuY2zNgEQoBPM5
jAe1pzZKbBc2cAhylvWW86/ZRdOjVAV5StTwlkwGS76WUnusdy1Iisjmk5nB1arJ
CcijrCfZBuqDzBWp3qSrBEZCqBUfQVz4CWGHWF91iaEw+y+5PcQJ7Pn9DMRqMwsY26
CCUCzwpXrZ/XUqlIyqpvIGHMiQKBgQC9Y2/bYhifC337sc1F3jbC0soSehURyOYA
39hUkMQwlPZ9CH3m8Q4pUK+wQdzGxsK8Iqu+H6iGqt2BN881Mg11GtVvaplermH1
KlxOFeT+sewiED6BtMntDUByxTkP66hE/FUKEqMfTaykD+F7jPel/mssMTch8WFY
x373xhu1+QKBgGYVkvPmB9fruGJEjpdFn+L4vB0PkIN1dAxg38NBj3Ap16835wqZ
pCi1Voa2doGHgWlL7IpuMacB+3AeAEq7vm+etszjuwswGZ4w4qwYNRQVMiqHwVZ6
1NLDFGzss5nvFUQDgwqVE-6aMBLYLbQv6i5N5y7bC5SajqSjHPzt8UJUqbZ8a+aU
Y8Deutq05tj4GyMVUQDgwqVE-6aMBLYLbQv6i5N5y7bC5SajqSjHPzt8UJUqbZ8a
bxjU26x2EdLS6Br6WYxG++48z4b6L41NxHStypMKAY28OS30+ka1lehsprUsNhKY
dUtOsrFAdKteAJQ3ayTT3ELnqhZytuP+VTVKHvECgYAodZFheozcxZNOGqKrah3x
ujsYt9bUxCRrNmB8+IQtYSLMcXe9nDrSPEd+54quwFdpMyr81sW9YWgFjQMgg1FE
2j/Jw8lCcijrCfZBuqDzBWp3qSrBEZCqBUfQVz4CWGHWF91iaEw+b2b0KhoYonG/K
X7pw3WgRiUfuexLb7SCj3Q==
-----END PRIVATE KEY-----";

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = YaspaBanking::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount'           => 'required|string|min:1',
            'currency'         => 'required|string|in:EUR,GBP',
            'redirectUrl'  => 'nullable|url|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $customer = strtoupper(uniqid());
        $reference = 'REF' . strtoupper(bin2hex(random_bytes(5)));

        $body = [
            'customerIdentifier'  => $customer,
            'amount'  => $request->input('amount'),
            'currency' => $request->input('currency'),
            'paymentGiro' => ($request->input('currency') == 'EUR') ? 'SEPA' : 'FPS',
            'reference' => $reference,
            'description' => $request->input('description'),
            'journeyType' => 'ENHANCED_PAY_IN',
            'successRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'failureRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'successBankRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'failureBankRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url
        ];

        $client = new Client();

        try {
            $response = $client->post($this->baseURL . '/v2/payins/hosted-payin/generate-link', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'AuthorizationCitizen' => $this->apiKey
                ],
                'json' => $body,
                'verify' => false
            ]);

            $paymentLink = trim((string) $response->getBody());

            if (!empty($paymentLink)) {
                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p20')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $request->input('currency');
                $trans->amount         = $request->input('amount');
                $trans->checkout_id    = $uuid;
                $trans->customer_details = $customer;
                $trans->payment_status = 'Pending';
                $trans->description     = $reference;
                $trans->status         = 'p20';

                $trans->save();

                $responseData = [
                    "success"     => true,
                    "amount"      => number_format($request->input('amount'), 2),
                    "currency"    => $request->input('currency'),
                    "checkout_id" => $uuid,
                    "link"        => $paymentLink,
                ];

                return response()->json($responseData, 200);
            }
        } catch (ClientException $e) {
            $status = $e->getResponse()?->getStatusCode() ?? 400;
            $body   = (string) ($e->getResponse()?->getBody() ?? '');

            Log::error('Yaspa Payment Request Failed (ClientException)', [
                'status' => $status,
                'body'   => $body,
            ]);

            return response()->json([
                'error'  => 'Payment Request Failed',
                'status' => $status,
                'body'   => $body,
            ], $status);
        } catch (Exception $e) {
            Log::error('Yaspa Payment Request Failed (Exception)', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error'   => 'Payment Request Failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function CreatePayout(Request $request, $accId)
    {
        $checkacc = YaspaBanking::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount'           => 'required|string|min:1',
            'currency'         => 'required|string|in:EUR,GBP',
            'redirectUrl'  => 'nullable|url|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $customer = strtoupper(uniqid());
        $reference = 'REF' . strtoupper(bin2hex(random_bytes(5)));

        $body = [
            'customerIdentifier'  => $customer,
            'amount'  => $request->input('amount'),
            'currency' => $request->input('currency'),
            'paymentGiro' => ($request->input('currency') == 'EUR') ? 'SEPA' : 'FPS',
            'reference' => $reference,
            'description' => $request->input('description'),
            'isQueued' => false,
            'successRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'failureRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'successBankRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url,
            'failureBankRedirectUrl'   => $request->input('redirectUrl') ?? $checkacc->redirect_url
        ];

        $endpoint = $this->baseURL . "/v2/payouts/hosted-payout/generate-link";
        $signatureExpireTime = time() + 590;
        $requestBodyJson = json_encode($body, JSON_UNESCAPED_SLASHES);

        $signaturePlainText =
            $signatureExpireTime . "|POST|" . $endpoint . "|" . $requestBodyJson;

        $privateKey = openssl_pkey_get_private($this->rsaSecret);

        if (!$privateKey) {
            Log::error('Yaspa Payout Invalid private key: ' . openssl_error_string());
        }

        $signatureBinary = '';
        openssl_sign($signaturePlainText, $signatureBinary, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        $encodedSignature = base64_encode($signatureBinary);

        $client = new Client();

        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'AuthorizationCitizen' => $this->apiKey,
                    'Signature' => $encodedSignature,
                    'Expires-at' => $signatureExpireTime
                ],
                'body' => $requestBodyJson,
                'verify' => false
            ]);

            $paymentLink = trim((string) $response->getBody());

            if (!empty($paymentLink)) {
                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p20')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $request->input('currency');
                $trans->amount         = $request->input('amount');
                $trans->checkout_id    = $uuid;
                $trans->customer_details = $customer;
                $trans->payment_status = 'Pending';
                $trans->description     = $reference;
                $trans->status         = 'p20';

                $trans->save();

                $responseData = [
                    "success"     => true,
                    "amount"      => number_format($request->input('amount'), 2),
                    "currency"    => $request->input('currency'),
                    "checkout_id" => $uuid,
                    "link"        => $paymentLink,
                ];

                return response()->json($responseData, 200);
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorBody = $e->getResponse()->getBody()->getContents();
            } else {
                $errorBody = $e->getMessage();
            }

            Log::error('Yaspa Payout Error: ' . $errorBody);

            return response()->json([
                'status'  => $e->getCode(),
                'error'   => $errorBody,
            ]);
        }
    }

    public function handleNotification(Request $request)
    {
        Log::info('Yaspa webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('customer_details', $request->input('data.customerIdentifier'))->where('status', 'p20')->first() ?: new Transaction();
        $status = $request->input('data.transactionStatus');
        $err = trim((string) $request->input('data.errorReason', ''));
        $type   = strtoupper((string) $request->input('data.journeyType', ''));

        $trans->currency       = ($type === 'HOSTED_PAYOUT') ? $request->input('data.currency') : $request->input('data.paymentCurrency');
        $trans->amount         = ($type === 'HOSTED_PAYOUT') ? $request->input('data.amount') : $request->input('data.paymentAmount');
        $trans->payment_id      = $request->input('data.citizenTransactionId');
        $trans->description    = $err !== '' ? $err : $request->input('data.reference');
        $trans->status         = 'p20';

        if ($type === 'HOSTED_PAYOUT' && $status === 'ACCEPTED') {
            $trans->payment_status = 'Complete';
        } else {
            $trans->payment_status = ucwords(strtolower(str_replace('_', ' ', $status)));
        }

        $trans->save();

        $account = YaspaBanking::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p20/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("P20 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P20 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = YaspaBanking::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p20')->first();
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
