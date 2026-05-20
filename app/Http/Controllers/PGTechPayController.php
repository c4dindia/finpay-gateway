<?php

namespace App\Http\Controllers;

use App\Models\PTwelvePaymentMethod;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PGTechPayController extends Controller
{
    protected $baseURL = 'https://payment.pgpaytech.com/api/v1';

    public function h2hPgTechPay(Request $request, $accId): JsonResponse
    {
        $checkaccId = PTwelvePaymentMethod::where('accountId', $accId)->where('status', '1')->first();

        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        // if($checkaccId->accountId == 'ry6hgf43ws6u7bi8cczqNVKXj4Aix2nWIkCVqi')
        // {
        //     return response()->json(["success" => false, "message" => "Test account not available for production"],501);
        // }

        $request->validate([
            'amount' => 'required',
            'currency' => 'required|string|size:3',
            'email' => 'required|email',
            'phoneNumber' => 'required|string|max:15',
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            "ipAddress" => 'required',
            "customer_uuid" => 'nullable|string|max:64',
            'redirectUrl' => 'nullable|url',
            // IMPORTANT: treat card numbers as string
            'cardNumber' => ['required', 'digits_between:13,19'],
            'cardExpiryMonth'  => ['required', 'regex:/^(0[1-9]|1[0-2])$/'],
            'cardExpiryYear'   => ['required', 'regex:/^\d{4}$/'],
            'cardCvvNumber' => 'required|digits_between:3,6',
        ], [
            'cardExpiryMonth.regex' => 'Expiry month must be in MM format (01-12).',
            'cardExpiryYear.regex' => 'Expiry year must be in YYYY format (four digits).',
            'cardCvvNumber.digits_between' => 'PIN must be between 3 and 6 digits.',
            'cardNumber.digits_between' => 'Card number must be between 13 and 19 digits.',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $payload = [
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "address" => $request->address,
            "country" => $request->country,
            "state" => $request->state,
            "city" => $request->city,
            "zip" => $request->zip,
            "ipAddress" => $request->ipAddress,
            "email" => $request->email,
            "phoneNumber" => $request->phoneNumber,
            "amount" => $request->amount,
            "currency" => $request->currency,
            "cardNumber" => $request->cardNumber,
            "cardExpiryMonth" => $request->cardExpiryMonth,
            "cardExpiryYear" => $request->cardExpiryYear,
            "cardCvvNumber" => $request->cardCvvNumber,
            "redirectUrl" => $request->redirectUrl,
            "notifyUrl" => route('pgtechpayNotification'),
            "merchantRef" => $uuid
        ];
        if($request->filled('customer_uuid'))
        {
            $payload["submerchant_id"] = $request->customer_uuid;
        }

        $token = $checkaccId->token ?? null;
        if($request->currency == 'CAD'){
            $token = 'b0467c48-abee-4699-ab6e-c70c2aba8191';
        }
        if($token == null){
            return response()->json(["success" => false, "message" => "MID not assigned by provider"],501);
        }

        $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p12')->first() ?: new Transaction();

        $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = $request->currency;
        $trans->amount         = $request->amount;
        $trans->checkout_id    = $uuid;
        $trans->payment_status = 'Created';
        $trans->status         = 'p12';

        $trans->save();

        try {
            $client = new Client();
            $response = $client->post($this->baseURL .'/create/transaction', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization'=> 'Bearer '. $token
                ],
                'json' => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::channel('pgtechpay')->info("PG Tech Pay response body API: Status Code - {$statusCode} , Create Trxn api ", $responseBody);

            $status = $responseBody['status'] ?? null;
            $message = $responseBody['message'] ?? null;

            if ($status == "failed") {
                Log::channel('pgtechpay')->warning("PG Tech Pay Payment Api Status Failed , Status Code {$statusCode} :", $responseBody);

                return response()->json([
                    'success' => false,
                    'status'  => $status,
                    'checkout_id' => $trans->checkout_id,
                    'message' => $message
                ], 502);
            }

            $threeDSurl     = $responseBody['3dsUrl'] ?? null;
            $descriptor     = $responseBody['descriptor'] ?? null;
            $transactionRef = $responseBody['transactionRef'] ?? null;

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;

            $errorBody  = [];
            if ($e->getResponse()) {
                $errorBodyRaw = (string) $e->getResponse()->getBody();
                $errorBody    = json_decode($errorBodyRaw, true) ?: ['raw_body' => $errorBodyRaw];
            }

            Log::channel('pgtechpay')->warning("PG Tech Pay Payment Api Failed , Status Code $statusCode :", [
                'exception_message' => $e->getMessage(),
                'error_body'        => $errorBody,
            ]);

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'checkout_id' => $trans->checkout_id,
                'message' => $errorBody['message'] ?? 'Payment provider error. Please try again.',
            ], 502);
        }

        $trans->payment_id     = $transactionRef;
        $trans->payment_status = ucfirst(strtolower($status)) ?? 'Created';
        $trans->status         = 'p12';

        $trans->save();

        Log::channel('pgtechpay')->info("Transction Initialization Successful.");

        $responseData = [
            "success"     => true,
            "amount"      => $request->amount,
            "currency"    => $request->currency,
            "checkout_id" => $uuid,
            "payment_id"  => $transactionRef,
            "status"      => ucfirst(strtolower($status)),
        ];

        if ($threeDSurl !== null) {
            $responseData['link'] = $threeDSurl;
        }

        return response()->json($responseData, 200);
    }

    public function h2hPgTechPayTest(Request $request, $accId): JsonResponse
    {
        $checkaccId = PTwelvePaymentMethod::where('accountId', $accId)->where('status', '1')->first();

        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        if($checkaccId->accountId != 'ry6hgf43ws6u7bi8cczqNVKXj4Aix2nWIkCVqi')
        {
            return response()->json(["success" => false, "message" => "Use Test account in Sandbox"],501);
        }

        $request->validate([
            'amount' => 'required',
            'currency' => 'required|string|size:3',
            'email' => 'required|email',
            'phoneNumber' => 'required|string|max:15',
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            "ipAddress" => 'required',
            "customer_uuid" => 'nullable|string|max:64',
            'redirectUrl' => 'nullable|url',
            // IMPORTANT: treat card numbers as string
            'cardNumber' => ['required', 'digits_between:13,19'],
            'cardExpiryMonth'  => ['required', 'regex:/^(0[1-9]|1[0-2])$/'],
            'cardExpiryYear'   => ['required', 'regex:/^\d{4}$/'],
            'cardCvvNumber' => 'required|digits_between:3,6',
        ], [
            'cardExpiryMonth.regex' => 'Expiry month must be in MM format (01-12).',
            'cardExpiryYear.regex' => 'Expiry year must be in YYYY format (four digits).',
            'cardCvvNumber.digits_between' => 'PIN must be between 3 and 6 digits.',
            'cardNumber.digits_between' => 'Card number must be between 13 and 19 digits.',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $payload = [
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "address" => $request->address,
            "country" => $request->country,
            "state" => $request->state,
            "city" => $request->city,
            "zip" => $request->zip,
            "ipAddress" => $request->ipAddress,
            "email" => $request->email,
            "phoneNumber" => $request->phoneNumber,
            "amount" => $request->amount,
            "currency" => $request->currency,
            "cardNumber" => $request->cardNumber,
            "cardExpiryMonth" => $request->cardExpiryMonth,
            "cardExpiryYear" => $request->cardExpiryYear,
            "cardCvvNumber" => $request->cardCvvNumber,
            "redirectUrl" => $request->redirectUrl,
            "notifyUrl" => route('pgtechpayNotification'),
            "merchantRef" => $uuid
        ];
        if($request->filled('customer_uuid'))
        {
            $payload["submerchant_id"] = $request->customer_uuid;
        }

        $token = $checkaccId->token ?? null;
        if($request->currency == 'CAD'){
            $token = 'b0467c48-abee-4699-ab6e-c70c2aba8191';
        }
        if($token == null){
            return response()->json(["success" => false, "message" => "MID not assigned by provider"],501);
        }

        try {
            $client = new Client();
            $response = $client->post($this->baseURL .'/test/create/transaction', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization'=> 'Bearer '. $token
                ],
                'json' => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::channel('pgtechpay')->info("PG Tech Pay response body API: Status Code - {$statusCode} , Create Trxn api ", $responseBody);

            $status = $responseBody['status'] ?? null;
            $message = $responseBody['message'] ?? null;

            if ($status == "failed") {
                Log::channel('pgtechpay')->warning("PG Tech Pay Payment Api Status Failed , Status Code {$statusCode} :", $responseBody);

                return response()->json([
                    'success' => false,
                    'status'  => $status,
                    'message' => $message
                ], 502);
            }

            $threeDSurl     = $responseBody['3dsUrl'] ?? null;
            $descriptor     = $responseBody['descriptor'] ?? null;
            $transactionRef = $responseBody['transactionRef'] ?? null;

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;

            $errorBody  = [];
            if ($e->getResponse()) {
                $errorBodyRaw = (string) $e->getResponse()->getBody();
                $errorBody    = json_decode($errorBodyRaw, true) ?: ['raw_body' => $errorBodyRaw];
            }

            Log::channel('pgtechpay')->warning("PG Tech Pay Payment Api Failed , Status Code $statusCode :", [
                'exception_message' => $e->getMessage(),
                'error_body'        => $errorBody,
            ]);

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => $errorBody['message'] ?? 'Payment provider error. Please try again.',
            ], 502);
        }

        $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p12')->first() ?: new Transaction();

        $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = $request->currency;
        $trans->amount         = $request->amount;
        $trans->checkout_id    = $uuid;
        $trans->payment_id     = $transactionRef;
        if($trans->payment_status == null){
            $trans->payment_status = ucfirst(strtolower($status));
        }
        $trans->status         = 'p12';

        $trans->save();

        Log::channel('pgtechpay')->info("Transction Initialization Successful.");

        $responseData = [
            "success"     => true,
            "amount"      => $request->amount,
            "currency"    => $request->currency,
            "checkout_id" => $uuid,
            "payment_id"  => $transactionRef,
            "status"      => ucfirst(strtolower($status)),
        ];

        if ($threeDSurl !== null) {
            $responseData['link'] = $threeDSurl;
        }

        return response()->json($responseData, 200);
    }

    public function handleNotification(Request $request)
    {
        Log::channel('pgtechpay')->info('PG Tech Pay webhook request : ',$request->all());

        $data = $request->input('data', []);
        $payment = $data['payment'] ?? [];
        $client = $data['client'] ?? [];
        $card = $data['card'] ?? [];

        if (empty($payment)) {
            Log::channel('pgtechpay')->warning('PG Tech Pay webhook: payment data missing', $request->all());
            return response()->json(['success' => true], 403);
        }

        $firstName = $client['firstName'] ?? null;
        $lastName = $client['lastName'] ?? null;
        $fullName = $firstName .' '. $lastName;
        $name = $fullName;
        $email = $client['email'] ?? null;
        $phone = $client['phoneNumber'] ?? null;
        $message = $payment['message'] ?? null;

        $trans = Transaction::where('checkout_id', $payment['merchantRef'])->where('status', 'p12')->first() ?: new Transaction();

        $trans->currency       = $payment['currency'];
        $trans->amount         = $payment['amount'];
        $trans->checkout_id    = $payment['merchantRef'] ?? null;
        $trans->payment_id     = $payment['transactionRef'] ?? null;
        $trans->payment_status = ucfirst(strtolower($payment['status']));
        $trans->description    = ucfirst(strtolower($payment['status'])) . ' - ' . $message;
        $trans->customer_details= Str::of("Name: {$name} , Email: {$email} , Phone: {$phone}")->squish()->trim();
        $trans->card_number    = $card['cardNumber'] ?? null;
        $trans->status         = 'p12';

        $trans->save();

        $account = PTwelvePaymentMethod::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::channel('pgtechpay')->warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/finpay/p12/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::channel('pgtechpay')->info("P12 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::channel('pgtechpay')->warning("P12 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId , $checkout_id)
    {
        $checkaccId = PTwelvePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p12')->first();
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

