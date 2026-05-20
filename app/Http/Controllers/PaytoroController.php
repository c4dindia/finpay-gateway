<?php

namespace App\Http\Controllers;

use App\Models\PaytoroPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class PaytoroController extends Controller
{
    protected $baseURL = 'https://connect.paytoro.io/api/request/create';

    public function h2hPaytoro(Request $request, $accId)
    {
        $checkaccId = PaytoroPayment::where('accountId', $accId)->where('status', '1')->first();

        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'email' => 'required|email',
            'mobile' => 'required|string|max:15',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'return_url' => 'nullable|url',
            // IMPORTANT: treat card numbers as string
            'card_holder_name' => 'required|string|max:100',
            'card_type' => 'required|string|max:50',
            'card_number' => ['required', 'digits_between:13,19'],
            'expiry_month'  => ['required', 'regex:/^(0[1-9]|1[0-2])$/'],
            'expiry_year'   => ['required', 'regex:/^\d{2}$/'],
            'cvv' => 'required|digits_between:3,6',
        ], [
            'expiry_month.regex' => 'Expiry month must be in MM format (01-12).',
            'expiry_year.regex' => 'Expiry year must be in YY format (two digits).',
            'pin.digits_between' => 'PIN must be between 3 and 6 digits.',
            'card_number.digits_between' => 'Card number must be between 13 and 19 digits.',
        ]);

        do {
            $uuid = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $signaturePlainParts = [
            $checkaccId->auth_key,
            $uuid,
            $request->currency,
            $checkaccId->secret_key,
        ];

        $signaturePlain = implode('||', $signaturePlainParts);
        $signature      = hash('sha256', $signaturePlain);

        Log::info('Signature Plain: ' . $signaturePlain);

        $payload = [
            'request_mode' => 'PayIn',

            'request_payload' => [
                'request_authkey'        => $checkaccId->auth_key,
                'request_flow'           => 'direct',
                'request_payment_method' => 'card',
                'request_signature'      => $signature,

                'payment_method_payload' => [
                    'card_holder_name' => $request->card_holder_name,
                    'card_type'        => strtolower($request->card_type),
                    'card_number'      => $request->card_number,
                    'expiry_month'     => $request->expiry_month,
                    'expiry_year'      => $request->expiry_year,
                    'cvv'              => $request->cvv,
                ],
                'customer_payload' => [
                    'first_name'  => $request->first_name,
                    'last_name'   => $request->last_name,
                    'email'       => $request->email,
                    'mobile'      => $request->mobile,
                    'address'     => $request->address,
                    'city'        => $request->city,
                    'state'       => $request->state,
                    'postal_code'    => $request->postal_code,
                    'country'     => $request->country,
                    'ip_address'  => $request->ip(),
                ],
                'payment_payload' => [
                    'payment_ref_id'    => $uuid,
                    'request_amount'    => $request->amount,
                    'currency'          => $request->currency,
                    'notification_url'  => 'https://payment.ryzen-pay.com/api/p11/notification',
                    'return_url'        =>  $request->return_url ?? $checkaccId->redirect_url,
                ],
                'risk_payload' => [
                    'category_class'      => 'VIP',
                    'device_fingerprint'  => 'NA',
                ],
            ],
        ];

        try {
            $client = new Client();
            $response = $client->post($this->baseURL, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'verify' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info("Paytoro response body API: Status Code - {$statusCode} , Paytoro payment api ", $responseBody);

            $status = $responseBody['success'] ?? null;

            if ($status !== true) {
                Log::warning("Paytoro Payment Api Status Failed , Status Code {$statusCode} :", $responseBody);

                return response()->json([
                    'success' => false,
                    'stage'   => 'initialize',
                    'message' => $responseBody['error_result'][0]['error_message'] ?? 'Unknown error from Service provider during payment.',
                    'code'    => $responseBody['error_result'][0]['error_code'] ?? null,
                    'raw'     => $responseBody,
                ], 502);
            }

            $transactionId = $responseBody['response_payload']['payment_result']['payment_id'] ?? null;
            $paymentStatus = $responseBody['response_payload']['payment_result']['payment_status'] ?? null;
            $paymentLink   = $responseBody['response_payload']['payment_result']['payment_link'] ?? null;

            if (!$transactionId) {
                Log::error("Paytoro payment response missing transactionId", $responseBody);

                return response()->json([
                    'success' => false,
                    'stage'   => 'initialize',
                    'message' => 'Payment initialization failed: missing transaction ID.',
                    'raw'     => $responseBody,
                ], 502);
            }
        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody  = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

            Log::warning("Paytoro Payment Api Failed , Status Code $statusCode :", $errorBody);

            return response()->json([
                'success' => false,
                'stage'   => 'initialize',
                'message' => $errorBody['message'] ?? 'Paytoro payment request failed.',
                'code'    => $errorBody['code'] ?? null,
                'raw'     => $errorBody,
            ], $statusCode);
        }

        $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p11')->first() ?: new Transaction();

        $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = $request->currency;
        $trans->amount         = $request->amount;
        $trans->checkout_id    = $uuid;
        $trans->payment_id     = $transactionId;
        $trans->payment_status = ucfirst(strtolower($paymentStatus));
        $trans->description    = ucfirst(strtolower($paymentStatus)) . ' - ' . $responseBody['response_payload']['payment_result']['payment_response_message'];
        $trans->status         = 'p11';

        $trans->save();

        Log::info("Transction Initialization Successful.");

        $responseData = [
            "success"     => true,
            "amount"      => $request->amount,
            "currency"    => $request->currency,
            "checkout_id" => $uuid,
            "payment_id"  => $transactionId,
            "status"      => ucfirst(strtolower($paymentStatus)),
        ];

        if ($paymentLink !== null) {
            $responseData['link'] = $paymentLink;
        }

        return response()->json($responseData, 200);
    }

    public function handleNotification(Request $request)
    {
        Log::info('Paytoro webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('checkout_id', $request->payment_ref_id)->where('status', 'p11')->first() ?: new Transaction();

        $trans->currency       = $request->currency;
        $trans->amount         = $request->payment_amount;
        $trans->checkout_id    = $request->payment_ref_id;
        $trans->payment_id     = $request->payment_id;
        $trans->payment_status = ucfirst(strtolower($request->payment_status));
        $trans->description    = ucfirst(strtolower($request->payment_status)) . ' - ' . $request->payment_response_message;
        $trans->status         = 'p11';

        $trans->save();

        $account = PaytoroPayment::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p11/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("P11 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P11 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId , $checkout_id)
    {
        $checkaccId = PaytoroPayment::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p11')->first();
        if($transaction == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

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
}
