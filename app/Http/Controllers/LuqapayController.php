<?php

namespace App\Http\Controllers;

use App\Models\PEightPaymentMethod;
use App\Models\Transaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LuqapayController extends Controller
{
    protected $luqapay_baseURL ='https://sandbox-wallet.luqapay.com' ; //sandbox
    // protected $luqapay_baseURL ='https://wallet.luqapay.com' ; //production

    // STEP 1: Create Checkout (Initialize Payment)
    public function createCheckout(Request $request, $accId)
    {
        $checkaccId = PEightPaymentMethod::where('accountId',$accId)->where('status','1')->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'email' => 'required|email',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'country' => 'required|string|size:2',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postCode' => 'required|string',
            'dateOfBirth' => 'required|date_format:Y-m-d',
            'failRedirectUrl' => 'nullable|url',
            'successRedirectUrl' => 'nullable|url'
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $payload = [
            "apiKey" => $checkaccId->luqapay_apikey,
            "amount" => number_format((float) $request->amount, 2, '.', ''),
            "country" => strtoupper($request->country),
            "currency" => strtoupper($request->currency),
            "dateOfBirth" => $request->dateOfBirth,
            "defaultPaymentMethod" => "CCDIRECT",
            "email" => $request->email,
            "failRedirectUrl" => $request->failRedirectUrl ?? route('viewLuqapayPaymentPage',$uuid),
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "language" => "EN",
            "address" => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            "postCode" => $request->postCode,
            "referenceNo" => $uuid,
            "successRedirectUrl" => $request->successRedirectUrl ?? route('viewLuqapayPaymentPage',$uuid),
            "is3d" => true,
            "only3d" => false,
        ];

        try{
            $client = new Client();
            $response = $client->post($this->luqapay_baseURL . '/v2/checkout/initialize',[
                'headers' => [
                'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(),true);
            Log::info(" response body API: Status Code - {$statusCode} , Luqapay initalize api ", $responseBody);

            if($responseBody['status'] == "ERROR"){
                Log::warning("Luqapay Checkout Initialize Api Status Failed , Status Code {$statusCode} :",$responseBody);

                return response()->json([
                    $responseBody
                ]);
            }

        }catch(RequestException $e){
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

            Log::warning("Luqapay Checkout Initialize Api Failed , Status Code $statusCode :",$errorBody);

            return response()->json([
                $errorBody
            ]);
        }

        $transactionId = $responseBody['transactionId'];

        $trans = Transaction::where('checkout_id',$uuid)->where('status','p8')->first() ?: new Transaction();

        $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = strtoupper($request->currency);
        $trans->amount         = round($request->amount, 2);
        $trans->from_currency  = strtoupper($request->currency);
        $trans->from_amount    = round($request->amount, 2);
        $trans->checkout_id    = $uuid;
        $trans->payment_id     = $transactionId;
        $trans->payment_status = 'Waiting';
        $trans->description    = 'Message: Transaction Initialized';
        $trans->status         = 'p8';
        $trans->token          = $responseBody['token'] ?? null;

        $trans->save();

        Log::info("Transction Initialization Successful.");

        return response()->json([
            "success" => true,
            "amount" => round($request->amount, 2),
            "currency" => strtoupper($request->currency),
            "checkout_id" => $uuid,
            "link" => "https://payment.ryzen-pay.com/payment/p8/payment-page/".$uuid
        ],200);
    }

    /**
     * STEP 2: Show Checkout page to input card details
     */
    public function viewPaymentPage($checkout_id)
    {
        $checkout = Transaction::where('checkout_id', $checkout_id)->first();
        if(!isset($checkout)){ return "Transaction Not Found!"; }

        return view('payment.luqapay.payment-page', compact('checkout'));
    }

    public function makeLuqapayPayment(Request $request, $checkout_id)
    {
        $trxn = Transaction::where('checkout_id',$checkout_id)->first();

        if(!isset($trxn)){
            return back()->with("error","Invalid transaction Request.");
        }

        try{
            $client = new Client();
            $payload = [
                "account" =>  [
                      "cardNumber" =>  $request->card_number,
                      "expDate" =>  $request->exp,
                      "pin" =>  $request->cvv
                    ],
                "customerInfo" =>  [
                    "ip" =>  $request->ip(),
                    "agent" =>  $request->header('User-Agent'),
                    "threeDsV2" => [
                         "acceptHeader" => $request->header('Accept') ?? "text/html,application/xhtml+xml,application/xml",
                         "javaEnabled" => true,
                         "language" => "en-EN",
                         "colorDepth" => 48,
                         "screenHeight" => $request->input('screenHeight', 1080),
                         "screenWidth" => $request->input('screenWidth', 1920),
                         "timeZoneOffset" => $request->input('timezone', 'UTC+0'),
                        ]
                    ],
                "paymentMethod" =>  "CCDIRECT"
            ];

            $response = $client->post($this->luqapay_baseURL . '/v2/checkout/pay',[
                'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => $trxn->token
                ],
                'json' => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(),true);
            Log::info(" response body API: Status Code - {$statusCode} , Luqapay PAY api ", $responseBody);

            if($responseBody['status'] == "ERROR"){
                Log::warning("Luqapay Checkout PAY Api Status Failed , Status Code {$statusCode} :",$responseBody);

                // return response()->json([
                //     $responseBody
                // ]);
                return back()->withInput()->with("error",$responseBody['message'] ?? "There was an error with the request!");
            }

        }catch(RequestException $e){
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

            Log::warning("Luqapay Checkout PAY Api Failed , Status Code $statusCode :",$errorBody);

            // return response()->json([
            //     $errorBody
            // ]);

            return back()->withInput()->with("error",$responseBody['message'] ?? "There was an error with the request!");
        }

        $trxn->payment_id     = $responseBody['transactionId'] ?? null;
        $trxn->payment_status = 'Processing';
        $trxn->description    = 'Message: Transaction Processing' ;
        $trxn->status         = 'p8';

        $trxn->save();

        if(isset($responseBody['redirectUrl'])){
            Log::info(" Luqapay Success Pay redirected to link");
            return redirect($responseBody['redirectUrl']);
        }

        Log::info("Luqapay Transction Pay API Successful.");

        return redirect('/payment/p8/thank-you-page/'. $checkout_id);
    }

    public function viewThankYouPage($checkout_id)
    {
        $checkout = Transaction::where('checkout_id',$checkout_id)->firstOrFail();
        return view('payment.luqapay.thank-you-page',compact('checkout'));
    }

    //get Payment Status API for Luqapay
    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = PEightPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p8')->first();
        if($transaction == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        try{
            $client = new Client();

            $response = $client->get($this->luqapay_baseURL . '/v3/merchants/integration/'. $transaction->payment_id .'/status',[
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'x-api-key' => $checkaccId->luqapay_apikey
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(),true);
            Log::info(" response body API: Status Code - {$statusCode} , Luqapay transaction status api ", $responseBody);

            if($responseBody['status'] == "ERROR"){
                Log::warning("Luqapay transaction status Api Status Failed , Status Code {$statusCode} :",$responseBody);

                return response()->json([
                    $responseBody
                ]);
            }

        }catch(RequestException $e){
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

            Log::warning("Luqapay transaction status Api Failed , Status Code $statusCode :",$errorBody);

            return response()->json([
                $errorBody
            ]);
        }


        $message = $transaction->description ?? null;
        if(isset($responseBody['declinationNote'])){
            $message = $responseBody['declinationNote'];
        }else{
            $message = "Customer: ". $responseBody['customer'] . " | Email: ". $responseBody['customerEmail'];
        }

        $transaction->currency       = strtoupper($responseBody['currency']);
        $transaction->amount         = round($responseBody['amount'], 2);
        $transaction->from_currency  = strtoupper($responseBody['currency']);
        $transaction->from_amount    = round($responseBody['amount'], 2);
        $transaction->fees           = $responseBody['fee'] ?? null;
        $transaction->payment_status = ucfirst(strtolower($responseBody['status']));
        $transaction->description    = $message;
        $transaction->card_number    = $responseBody['cardNumber'] ?? null;
        $transaction->status         = 'p8';

        $transaction->save();

        return response()->json([
            'data' => [
                "currency" => $transaction->currency,
                "amount" => number_format($transaction->amount,2),
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description,
                "created_at" => $transaction->created_at,
                "updated_at" => $transaction->updated_at
            ]
        ],200);
    }

    public function webhookNotification(Request $request)
    {
        Log::info("Luqapay webhook request");
        Log::info($request->all());

        $fullUrl = $request->fullUrl();
        Log::info('Webhook received on URL:', ['full_url' => $fullUrl]);

        $trans = Transaction::where('checkout_id',$request->referenceNo)->where('status','p8')->first() ?: new Transaction();

        // $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = strtoupper($request->currency);
        $trans->amount         = round($request->amount, 2);
        $trans->from_currency  = strtoupper($request->currency);
        $trans->from_amount    = round($request->amount, 2);
        $trans->checkout_id    = $request->referenceNo;
        $trans->payment_id     = $request->transactionId;
        $trans->payment_status = ucfirst(strtolower($request->status));
        $trans->description    = 'Message: '. $request->message ?? ' - ' .' | Status: ' . $request->status . ' | Code: ' . $request->code;
        $trans->card_number    = $request->ccardNumber ?? null;
        $trans->status         = 'p8';

        $trans->save();

        $account = PEightPaymentMethod::where('accountId',$trans->account_id)->first();
        if(!isset($account)){
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

                $webhook = new Client();
                $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p8/' . $trans->checkout_id, [
                    'headers' => $headers,
                    'timeout' => 15,
                ]);
                Log::info("P8 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P8 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ],200);
    }

    public function h2hLuqapay(Request $request, $accId)
    {
        $checkaccId = PEightPaymentMethod::where('accountId',$accId)->where('status','1')->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'email' => 'required|email',
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'country' => 'required|string|size:2',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postCode' => 'required|string|max:20',
            'dateOfBirth' => 'required|date_format:Y-m-d',
            'failRedirectUrl' => 'nullable|url',
            'successRedirectUrl' => 'nullable|url',
            // IMPORTANT: treat card numbers as string
            'card_number' => ['required', 'digits_between:13,19'],
            'expiryDate'  => ['required', 'regex:/^(0[1-9]|1[0-2])-(\d{2})$/'],
            'pin' => 'required|digits_between:3,6',
        ], [
            'expiryDate.regex' => 'Expiry date format must be MM-YY (e.g., 05-27).',
            'pin.digits_between' => 'PIN must be between 3 and 6 digits.',
            'card_number.digits_between' => 'Card number must be between 13 and 19 digits.',
            'dateOfBirth.date_format' => 'Date of birth must be in format YYYY-MM-DD.',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $payload = [
            "apiKey" => $checkaccId->luqapay_apikey,
            "amount" => number_format((float) $request->amount, 2, '.', ''),
            "country" => strtoupper($request->country),
            "currency" => strtoupper($request->currency),
            "dateOfBirth" => $request->dateOfBirth,
            "defaultPaymentMethod" => "CCDIRECT",
            "email" => $request->email,
            "failRedirectUrl" => $request->failRedirectUrl ?? route('viewLuqapayPaymentPage',$uuid),
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "language" => "EN",
            "address" => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            "postCode" => $request->postCode,
            "referenceNo" => $uuid,
            "successRedirectUrl" => $request->successRedirectUrl ?? route('viewLuqapayPaymentPage',$uuid),
            "is3d" => true,
            "only3d" => false,
        ];

        try{
            $client = new Client();
            $response = $client->post($this->luqapay_baseURL . '/v2/checkout/initialize',[
                'headers' => [
                'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(),true);
            Log::info(" response body API: Status Code - {$statusCode} , Luqapay initalize api ", $responseBody);

            $status = $responseBody['status'] ?? null;

            if ($status !== 'APPROVED') {
                Log::warning("Luqapay Checkout Initialize Api Status Failed , Status Code {$statusCode} :", $responseBody);

                return response()->json([
                    'success' => false,
                    'stage'   => 'initialize',
                    'message' => $responseBody['message'] ?? 'Unknown error from Service provider during initialization.',
                    'code'    => $responseBody['code'] ?? null,
                    'raw'     => $responseBody,
                ], 502);
            }

            $transactionId = $responseBody['transactionId'] ?? null;
            $token         = $responseBody['token'] ?? null;

            if (!$transactionId || !$token) {
                Log::error("Luqapay initialize response missing transactionId or token", $responseBody);

                return response()->json([
                    'success' => false,
                    'stage'   => 'initialize',
                    'message' => 'Payment initialization failed: missing transaction ID or token.',
                    'raw'     => $responseBody,
                ], 502);
            }

        }catch(RequestException $e){
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody  = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

            Log::warning("Luqapay Checkout Initialize Api Failed , Status Code $statusCode :", $errorBody);

            return response()->json([
                'success' => false,
                'stage'   => 'initialize',
                'message' => $errorBody['message'] ?? 'Luqapay INIT request failed.',
                'code'    => $errorBody['code'] ?? null,
                'raw'     => $errorBody,
            ], $statusCode);
        }

        $trans = Transaction::where('checkout_id',$uuid)->where('status','p8')->first() ?: new Transaction();

        $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = strtoupper($request->currency);
        $trans->amount         = round($request->amount, 2);
        $trans->from_currency  = strtoupper($request->currency);
        $trans->from_amount    = round($request->amount, 2);
        $trans->checkout_id    = $uuid;
        $trans->payment_id     = $transactionId;
        $trans->payment_status = "Waiting"; //ucfirst(strtolower($responseBody['status']));
        $trans->description    = 'Message: '. $responseBody['message'] .' | Status: ' . $responseBody['status'] . ' | Code: ' . $responseBody['code'];
        $trans->status         = 'p8';
        $trans->token          = $token;

        $trans->save();

        Log::info("Transction Initialization Successful.");

        try {
            $payload = [
                "account" => [
                    "cardNumber" => (string) $request->card_number,
                    "expDate"    => $request->expiryDate,
                    "pin"        => (string) $request->pin,
                ],
                "customerInfo" => [
                    "ip"    => $request->ip(),
                    "agent" => $request->header('User-Agent'),
                    "threeDsV2" => [
                        "acceptHeader"    => $request->header('Accept') ?? "text/html,application/xhtml+xml,application/xml",
                        "javaEnabled"     => true,
                        "language"        => "en-EN",
                        "colorDepth"      => 48,
                        "screenHeight"    => $request->input('screenHeight', 1080),
                        "screenWidth"     => $request->input('screenWidth', 1920),
                        "timeZoneOffset"  => $request->input('timezone', "UTC+2"),
                    ],
                ],
                "paymentMethod" => "CCDIRECT",
            ];

            $response = $client->post($this->luqapay_baseURL . '/v2/checkout/pay', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $token,
                ],
                'json' => $payload,
            ]);

            $statusCode   = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            Log::info("API: Status Code - {$statusCode} , Luqapay PAY api ", $responseBody);

            $payStatus  = $responseBody['status'] ?? null;
            $payCode    = $responseBody['code'] ?? null;
            $payMessage = $responseBody['message'] ?? null;

            if ($payStatus !== 'APPROVED') {
                // store failure status on our side
                $trans->payment_status = $payStatus ? ucfirst(strtolower($payStatus)) : 'Error';
                $trans->description    = 'Message: '.($payMessage ?? 'Unknown error').
                                         ' | Status: '.($payStatus ?? 'UNKNOWN').
                                         ($payCode ? ' | Code: '.$payCode : '');
                $trans->save();

                Log::warning("Luqapay Checkout PAY Api Status Failed , Status Code {$statusCode} :", $responseBody);

                return response()->json([
                    'success' => false,
                    'stage'   => 'pay',
                    'message' => $payMessage ?? 'Server payment failed.',
                    'code'    => $payCode,
                    'raw'     => $responseBody,
                ], 502);
            }

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody  = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

            Log::warning("Luqapay Checkout PAY Api Failed , Status Code $statusCode :", $errorBody);

            // mark transaction as failed
            $trans->payment_status = 'Error';
            $trans->description    = 'Luqapay PAY request exception. HTTP Status: '.$statusCode;
            $trans->save();

            return response()->json([
                'success' => false,
                'stage'   => 'pay',
                'message' => $errorBody['message'] ?? 'Service PAY request failed.',
                'code'    => $errorBody['code'] ?? null,
                'raw'     => $errorBody,
            ], $statusCode);
        }

        $trans->payment_status = 'Processing';
        $trans->description    = 'Message: Processing';

        if (!empty($responseBody['actualAmount'])) {
            $trans->amount = (float) $responseBody['actualAmount'];
        }
        if (!empty($responseBody['actualCurrency'])) {
            $trans->currency = $responseBody['actualCurrency'];
        }

        $trans->save();

        Log::info("Luqapay Transaction Pay API Successful.");

        $responseData = [
            "success"     => true,
            "amount"      => number_format($request->amount, 2, '.', ''),
            "currency"    => strtoupper($request->currency),
            "checkout_id" => $uuid,
            "payment_id"  => $trans->payment_id,
            // "status"      => $trans->payment_status,
        ];

        if (isset($responseBody['redirectUrl'])) {
            $responseData['link'] = $responseBody['redirectUrl'];
        }

        return response()->json($responseData, 200);
    }

    public function updatePaymentStatus()
    {
        $transactions = Transaction::where('status','p8')->get();
        $client = new Client();
        $i = 0;

        foreach($transactions as $trxn){

            $checkaccId = PEightPaymentMethod::where('accountId', $trxn->account_id)->where('status', '1')->first();
            if (!$checkaccId) {
                Log::warning("No active PSixPaymentMethod found for account ID: {$trxn->account_id}");
                continue;
            }

            try{
                $response = $client->get($this->luqapay_baseURL . '/v3/merchants/integration/'. $trxn->payment_id .'/status',[
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'x-api-key' => $checkaccId->luqapay_apikey
                    ]
                ]);

                $statusCode = $response->getStatusCode();
                $responseBody = json_decode($response->getBody()->getContents(),true);
                Log::info(" UPdate Luqapay Status API: Status Code - {$statusCode} : ", $responseBody);

                if($responseBody['status'] == "ERROR"){
                    Log::warning("Luqapay UPdate Status API: Status Failed , Status Code {$statusCode} :",$responseBody);

                    return response()->json([
                        $responseBody
                    ]);
                }

            }catch(RequestException $e){
                $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
                $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];

                Log::warning("Luqapay UPdate Status API: Failed , Status Code $statusCode :",$errorBody);

                return response()->json([
                    $errorBody
                ]);
            }

            $trxn->payment_status = ucfirst(strtolower($responseBody['status']));
            $trxn->card_number    = $responseBody['cardNumber'] ?? null;

            $trxn->save();
            $i++;
        }

        return response()->json([
            'success' => true,
            'message' => "All Luqapay Transactions Statuses Updated",
            'transactions' => $i
        ],201);

    }
}
