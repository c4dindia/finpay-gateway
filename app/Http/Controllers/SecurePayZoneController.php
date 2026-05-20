<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\PSevenPaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SecurePayZoneController extends Controller
{
    protected $securePayZoneSecretKey = '6252a0b6-8ac0-4f6e-8c2b-7c8816c4164f'; //sandbox
    protected $securePayZoneAuthKey = '93222395-55c9-4fa6-9566-9a82e9608a0e';   //sandbox

    //protected $securePayZoneSecretKey = 'aff0945d-34df-489a-89b7-da0e262b64dc'; //production
    //protected $securePayZoneAuthKey = '1924aad9-eb60-4c6f-b574-12c6ef768957';   //production
    protected $baseURL = 'https://api.securepayzone.com/api/';    //production  https://api.securepayzone.com/api/request/create


    //Webhook Notification from Secure Pay Zone
    public function handlePaymentNotification(Request $request)
    {
        $data = $request->all();
        $paymentId = $data['payment_id'];
        $refId = $data['payment_ref_id'];
        $status = $data['payment_status'];
        $payment_response_message = $data['payment_response_message'] ?? $status;
        $amount = $data['payment_amount'];
        $amountX100 = $data['payment_amount'] * 100; // multiplied by 100
        $currency = $data['currency'];

        $checkoutData = CheckoutDetail::where('checkout_id',$refId)->where('payment_partner','p7')->first();
        if(!$checkoutData){
            return response()->json(['error' => "Transaction Expired!"],403);
        }
        $p7user = PSevenPaymentMethod::where('accountId',$checkoutData->accId)->first() ;
        if(!$p7user){
            return response()->json(['error' => "Unkown User!"],403);
        }

        $secretKey = $p7user->spz_secretkey;
        $authKey = $p7user->spz_authkey;

        $plainText = "{$authKey}||{$paymentId}||{$status}||{$amountX100}||{$refId}||{$currency}||{$secretKey}";

        $generatedSignature = hash('sha256', $plainText);

        $checkoutData = CheckoutDetail::where('checkout_id',$refId)->first();
        $trxn = Transaction::where('checkout_id', $refId)->first() ?: new Transaction();

        $trxn->account_id= $checkoutData->accId;
        $trxn->currency= $currency; // currency in which we recieved in
        $trxn->amount= $amount;  // amount in which we recieved in
        // $trxn->from_currency=  $quoteCurrencySymbol; //cuurency user paid in
        // $trxn->from_amount= $amount; //amount user paid in
        // $trxn->net_amount= $net_amount;
        // $trxn->fees= $fees;
        $trxn->checkout_id= $refId;
        $trxn->payment_id= $paymentId;
        $trxn->payment_status= ucfirst(strtolower($status));
        $trxn->description= $payment_response_message;
        $trxn->status= 'p7';

        $trxn->save();

        if ($generatedSignature !== $data['request_signature']) {
            Log::warning("Invalid signature in  SecurePay Zone Notifictaion Webhook : ", ['expected' => $generatedSignature, 'received' => $data['request_signature']]);
        }

        Log::info("Payment Notification Received", $data);

        try{
            $detail = PSevenPaymentMethod::where('accountId',$trxn->account_id)->first();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => $detail->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p7/'.$trxn->checkout_id, [
                'headers' => $headers,
            ]);
            $statusCode = $resp->getStatusCode();
            Log::info("received P7 status-code response. Status code: {$statusCode}");

        }
        catch(RequestException $e){
            Log::warning("response p7 exception catch in sending webhook to our user : ".$e->getMessage());
        }

        return response()->json(['status' => 'success'], 200);
    }

    //api for creating checkout
    public function createCheckout(Request $request, $accId)
    {
        $date = Carbon::now()->subDays(30);
        CheckoutDetail::where('payment_partner','p7')->where('created_at', '<', $date)->delete();

        $checkaccId = PSevenPaymentMethod::where('accountId',$accId)->where('status','1')->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $request->validate([
            'currency' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0.01',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (CheckoutDetail::where('checkout_id', $uuid)->exists());

        $checkoutData = new CheckoutDetail();

        $checkoutData->accId = $checkaccId->accountId;
        $checkoutData->payment_partner = 'p7';
        $checkoutData->amount = round($request->amount, 2);
        $checkoutData->currency = strtoupper($request->currency);
        $checkoutData->checkout_id = $uuid;
        $checkoutData->status = '0';

        $checkoutData->save();

        return response()->json([
            "success" => true,
            "amount" => round($request->amount, 2),
            "currency" => strtoupper($request->currency),
            "checkout_id" => $uuid,
            "link" => "https://payment.ryzen-pay.com/payment/p7/payment-page/".$uuid
        ],200);
    }

    //public function viewPaymentPage
    public function viewPaymentPage($checkout_id)
    {
        $checkoutData = CheckoutDetail::where('checkout_id', $checkout_id)->where('payment_partner','p7')->firstOrFail();
        return view('payment.securepayzone.payment-page', compact('checkoutData'));
    }

    public function generateQRcode(Request $request)
    {
        // try {
        // $validated = $request->validate([
        //     'country_code' => 'required|string|size:2',
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'email' => 'required|email',
        //     'mobile' => 'required|string|max:20',
        // ]);
        // } catch (ValidationException $e) {
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $e->errors() // Returns array of field => [messages]
        //     ], 422);
        // }

        // $checkoutData = CheckoutDetail::where('checkout_id',$request->checkout_id)->first();
        // $payment_method = "QR";
        // $plainText = "{$this->securePayZoneAuthKey}||{$checkoutData->checkout_id}||{$checkoutData->currency}||{$this->securePayZoneSecretKey}";
        // $request_signature  = hash('sha256', $plainText);

        // $body = [
        //     "request_mode" => "payin",
        //     "request_payload"=> [
        //         "request_authkey"=> $this->securePayZoneAuthKey,
        //         "request_flow"=> "direct",
        //         "request_payment_method"=> $payment_method, //[Card / UPI / QR]
        //     "request_signature" => $request_signature,
        //     "customer_payload" => [
        //         "first_name" => $request->first_name,
        //         "last_name" => $request->last_name,
        //         "email" => $request->email,
        //         "mobile" => $request->mobile,
        //         "country" => $request->country_code,   //["US","IN","UK"],
        //         "ip_address" => $request->ip(),
        //     ],
        //     "payment_payload" => [
        //         "payment_ref_id" => $request->checkout_id,
        //         "request_amount" => $request->amount,
        //         "currency" => $request->currency,
        //         "notification_url" => "https://payment.ryzen-pay.com/api/payment/p7/notification",
        //         "return_url"  => "https://payment.ryzen-pay.com/payment/p7/payment-page/". $request->checkout_id
        //     ],
        //     "risk_payload" => [
        //         "category_class" => "VIP",
        //         "device_fingerprint" => "NA"
        //     ],
        //     "custom_field1" => "1111",
        //     "custom_field2" => $request->checkout_id,
        //     "custom_field3" => $request->amount,
        //     "custom_field4" => "string",
        //     "custom_field5" => "string",
        //     "custom_field6" => "string",
        //     "custom_field7" => "string",
        //     "custom_field8" => "string",
        //     "custom_field9" => "string",
        //     "custom_field10" => "string"
        //     ]
        // ];

        // try{
        //     $client = new Client();

        //     $response = $client->post($this->baseURL. 'request/create',[
        //         'headers' => [
        //             'Content-Type' => 'application/json',
        //         ],
        //         'json' => $body
        //     ]);

        //     $responseBody = json_decode($response->getBody()->getContents(),true);
        //     Log::info("response body API: QR mode", $responseBody);
        // }catch(RequestException $e) {
        //     Log::error("Error creating SecurePayZone Payment API :", (array)$e->getMessage());

        //     return response()->json([
        //         'success' => false,
        //         'error' => 'Failed to connect to payment gateway. Please try again later.'
        //     ], 500);
        // }

        // if($responseBody['success'] == false){

        //     $messages = [];

        //     if (isset($responseBody['error_result']) && is_array($responseBody['error_result'])) {
        //         foreach ($responseBody['error_result'] as $error) {
        //             if (isset($error['error_message'])) {
        //                 $messages[] = ucfirst($error['error_message']);
        //             }
        //         }
        //     }

        //     $finalMessage = implode(' | ', $messages) ?: 'Unknown error';

        //     $checkoutData->status = "1";
        //     $checkoutData->save();

        //     return response()->json(['error'=> $finalMessage],422);

        // } else {

        //     $responsePayload = $responseBody['response_payload'];
        //     $requestPaymentMethod = $responsePayload['request_payment_method'];
        //     $responseSignatureReceived = $responsePayload['response_signature'] ?? null;
        //     $paymentResult = $responsePayload['payment_result'];

        //     $payment_id = $paymentResult['payment_id'];
        //     $payment_status = $paymentResult['payment_status'];
        //     $payment_amount = $paymentResult['payment_amount'];
        //     $payment_amountX100 = $paymentResult['payment_amount']*100;
        //     $payment_link = $paymentResult['payment_link'] ?? null;
        //     $payment_html = $paymentResult['payment_html'] ?? null;
        //     $payment_response_message = $paymentResult['payment_response_message'];


        //     if($responseSignatureReceived != null ) {
        //         if($requestPaymentMethod == "CARD"){
        //             $plainText2 = "{$this->securePayZoneAuthKey}||{$checkoutData->checkout_id}||{$payment_id}||{$payment_status}||{$$checkoutData->currency}||{$payment_amountX100}||{$this->securePayZoneSecretKey}";
        //             $response_signature_generated  = hash('sha256', $plainText2);
        //             Log::info("Card generated signature : ". $response_signature_generated);
        //         }else {
        //             $plainText2 = "{$this->securePayZoneAuthKey}||{$checkoutData->checkout_id}||{$payment_id}||{$payment_status}||{$$checkoutData->currency}||{$payment_amountX100}||{$payment_link}||{$payment_html}||{$this->securePayZoneSecretKey}";
        //             $response_signature_generated  = hash('sha256', $plainText2);
        //             Log::info("UPI/QR generated signature : ". $response_signature_generated);
        //         }

        //         if($response_signature_generated != $responseSignatureReceived){
        //             Log::warning("Response signature didn't match", ["payment_method" => $requestPaymentMethod ,"responseSignatureReceived" => $responseSignatureReceived, "response_signature_generated" => $response_signature_generated]);
        //         }else{
        //             Log::info("Response Signature Matched for checkout_id : ". $checkoutData->checkout_id);
        //         }
        //     }

        //     $trxn = Transaction::where('checkout_id', $checkoutData->checkout_id)->first() ?: new Transaction();

        //     $trxn->account_id= $checkoutData->accId;
        //     $trxn->currency= $checkoutData->currency; // currency in which we recieved in
        //     $trxn->amount= $payment_amount;  // amount in which we recieved in
        //     $trxn->checkout_id= $checkoutData->checkout_id;
        //     $trxn->payment_id= $payment_id;
        //     $trxn->payment_status= ucfirst(strtolower($payment_status));
        //     $trxn->description= $payment_response_message;
        //     $trxn->status= 'p7';

        //     $trxn->save();

        //     $checkoutData->status = "1";
        //     $checkoutData->save();
        // }

        $payment_link = base64_decode("dXBpOi8vcGF5P3Zlcj0wMSZtb2RlPTE1JmFtPTYuMDAmY3U9SU5SJnBhPXdlcGF5aW4uYWpheXRyYWRlcnNAdGltZWNvc21vcyZwbj1BamF5IFRyYWRlcnMmbWM9NTY5MSZ0cj1XRVBBWTU4NTg5NDA4MDExNjUxMTImdG49UVIgU0lUIHRlc3RpbmcmbWlkPVdFUEFZNjM1NCZtc2lkPUFKQVktNzcwNCZtdGlkPVdFUEFZLTYzNTQ=");
        $payment_html = "iVBORw0KGgoAAAANSUhEUgAAAMgAAADIAQAAAACFI5MzAAACwElEQVR42u2Xva3rOhCEV2DATGqAANtgxpbkBvTTgNQSM7ZBgA3IGQNCe4fGxdFx8AJrk3cBC4Zh6DNAeXZ3dkz8Xxd9yZf8b0igrjDH9Ahm9DREIi0gkZ+MG/VR1OkqaYs7AkIPrpOjLtYu8qlNJyRRHc6eOpGuk5cTM/m8kFp0HVhIoAF+Pa+sNhzC7+p8SlCfaB7X661yHxNcxZBTryrx5t+66mMSePFmDoZIrYxzIIOEZMBTUw9FnX1GmryE0OhMVyBk68Su5DUKSMx7yIe3e0mjzivTzBKCCkNUuwd0TRpd7bWAtMvMJePAxaGv1eIlxB7ODBBA06RR9vzTiXdIqRMpDmqNihljlxctIM2r1EY0UV58GqJ9c76PSZojHz5vVDt8ZvujwR0S84rW1vlZ0NE0x0uDOySYXqvN4ZVGypBklpCCx6xt5rRdubZZ0QISDIrcBZoDfMUejQtIrPRyULzvrFaMoJaQhBN636YN9ZlgzFFA4E9tK6ITQZqVXj56g7SC0Oib0x++nUNeQjAZqSs0Eg3hTYM7pC0fFKc+8NMLL5Q6FhCkCdgVau5g9rX36Vd9PifwA4ephbvXFlWK3byIdAFjB6OCK5iezCNKCEy9rbJNqz3mk/KmBQTT5tJEaBy7NIPHgQISYcl42L8Q2/vBAgIBmjHT5OCjrbt/puQOiRV+MJQ2Ik1Ul649d4dgUSfy9vQt7PTabhLSIqfaiz0INU8D4ysCgsnQiHUMcrqMOb764A5RJ4QsWN2VHLYuLxLSKqxQ85Hsa2nQlcVukAbhT/BRuxB8q05eQFqGVSe90ivBks2oBQT5uvko2tnA+U79K1fdIbiHWIFDLKJxT7/m5ybBk5re2ye4h9PLSEEDIhGbmfPhrrxzi0ADM2DHFvzRyVi2exSQ0EwFse4gLA1okJ8S8v3H/yX/JvkDoSXNucQT4TQAAAAASUVORK5CYII";

        return response()->json([
            "success" => true,
            'payment_html' => $payment_html,
            'payment_link' => $payment_link,
        ],200);
    }

    public function getStatus($checkout_id)
    {
        $checkoutData = CheckoutDetail::where('checkout_id',$checkout_id)->where('payment_partner','p7')->first();

        if ($checkoutData->status == '0') {
            $status = "created";
            $message = "Transaction has not been processed yet!";
        } else {
            $status = "processed";

            $trxnData = Transaction::where('checkout_id', $checkout_id)->first();

            if ($trxnData) {
                $status = $trxnData->payment_status;
                $message = "Status: {$trxnData->payment_status} . Description: {$trxnData->description}.";
            } else {
                $message = "Transacation Expired!";
            }
        }

        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => $message
        ],200)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }

    function getCardType($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber); // Remove non-digits

        $cardTypes = [
            'VISA' => '/^4[0-9]{12}(?:[0-9]{3})?$/',                    // Visa
            'MASTERCARD' => '/^5[1-5][0-9]{14}$/',                        // MasterCard
            'AMEX' => '/^3[47][0-9]{13}$/',                                // American Express
            'DISCOVER' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',               // Discover
            'JCB' => '/^(?:2131|1800|35\d{3})\d{11}$/',                    // JCB
            'DINERS' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',              // Diners Club
            'MAESTRO' => '/^(5[0-9]{1}|6[0-9]{1})[0-9]{11,17}$/',          // Maestro
            'ENROUTE' => '/^2(?:014|149)[0-9]{11}$/',                      // EnRoute (a Discover brand)
            'VISA_ELECTRON' => '/^4026|4175|4508|4844|4913|4917[0-9]{12}$/', // Visa Electron
            'LASER' => '/^(6304|6706|6709|6771)[0-9]{10,15}$/',             // Laser (Ireland and UK)
            'CARTE_BLANCHE' => '/^389[0-9]{11}$/',                         // Carte Blanche
            'SWITCH' => '/^6759[0-9]{12}$/',                               // Switch
            'SOLO' => '/^6767[0-9]{12}$/',                                 // Solo (UK)
            'UK_MAESTRO' => '/^(5018|5020|5038|6304)[0-9]{11,16}$/',       // UK Maestro
            'BANKCARD' => '/^5610[0-9]{12}$/',                             // Bankcard (Australia)
            'ELO' => '/^(431274|451416|457393|504175|506699|509000|650031|650033)[0-9]{10}$/', // Elo (Brazil)
            'RU_PAY' => '/^60[0-9]{14}$/',                                 // RuPay (India)
            'UPI' => '/^1[0-9]{15}$/',                                     // UPI (Unified Payment Interface, India)
            'INTERAC' => '/^6703[0-9]{12}$/',                              // Interac (Canada)
            'SIR' => '/^637[0-9]{12}$/',                                   // Sir (South Korea)
            'ELECTRON' => '/^(4026|4175|4508|4844|4913|4917)[0-9]{12}$/',  // Electron (Visa variant)
            'BANK_OF_AMERICA' => '/^5[1-5][0-9]{14}$/',                    // Bank of America (MasterCard)
            'NOVA' => '/^5[1-5][0-9]{14}$/',                               // Nova (MasterCard)
            'CUP' => '/^62[0-9]{14,19}$/',                                 // China UnionPay (CUP)
            'AMEX_GC' => '/^37[0-9]{13}$/',                                // American Express Gift Cards
        ];

        foreach ($cardTypes as $type => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return $type;
            }
        }

        return 'UNKNOWN';
    }

    //making payment
    public function makeSZPayment(Request $request, $checkout_id)
    {
        $checkoutData = CheckoutDetail::where('checkout_id',$checkout_id)->where('payment_partner','p7')->first();
        if(!$checkoutData){
            return back()->with('error',"Transaction Expired!");
        }
        $p7user = PSevenPaymentMethod::where('accountId',$checkoutData->accId)->where('status',"1")->first() ;
        if(!$p7user){
             return back()->with('error',"Access Denied!");
        }

        $rules = [
            'country_code' => 'required|string|size:2',
            'payment_method' => 'required|string|in:UPI,CARD,QR',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string|max:20',
        ];

        // Conditional validation based on payment method
        if ($request->payment_method === 'CARD') {
            $rules = array_merge($rules, [
                'card_holder_name' => 'required|string|max:255',
                // 'card_type' => 'required|string|in:VISA,MASTERCARD,RUPAY', // adapt as needed
                'card_number' => 'required|digits_between:13,19',
                'expiry_month' => 'required|digits:2|between:1,12',
                'expiry_year' => 'required|digits:4|gte:' . date('Y'),
                'cvv' => 'required|digits_between:3,4',
            ]);
        }

        if ($request->payment_method === 'UPI') {
            $rules = array_merge($rules, [
                'upi_id' => 'required|string|max:255'
            ]);
        }

        $validated = $request->validate($rules,[
            'payment_method.required' => '*required',
        ]);

        $country_code = $request->country_code;
        $payment_method = $request->payment_method;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $mobile = $request->mobile;
        $amount = round($checkoutData->amount,2);
        $currency= $checkoutData->currency;
        $paymentRefId = $checkout_id;

        if($payment_method == "UPI"){
            $payment_method_payload = [
                "upi_id"=> $request->upi_id
            ];

            $plainText = "{$p7user->spz_authkey}||{$paymentRefId}||{$currency}||{$p7user->spz_secretkey}";
            $request_signature  = hash('sha256', $plainText);
        }

        if($payment_method == "CARD"){
            $payment_method_payload = [
                "card_holder_name" => $request->card_holder_name,
                "card_type" => $this->getCardType($request->card_number),
                "card_number" => preg_replace('/\D/', '', $request->card_number),
                "expiry_month" => $request->expiry_month ,
                "expiry_year" => $request->expiry_year ,
                "cvv" => $request->cvv
            ];

            $plainText = "{$p7user->spz_authkey}||{$paymentRefId}||{$currency}||{$p7user->spz_secretkey}";
            $request_signature  = hash('sha256', $plainText);
        }

        $body = [
            "request_mode" => "payin",
            "request_payload"=> [
                "request_authkey"=> $p7user->spz_authkey,
                "request_flow"=> "direct",
                "request_payment_method"=> $payment_method, //[Card / UPI]
                "payment_method_payload"=> $payment_method_payload,
            "request_signature" => $request_signature,
            "customer_payload" => [
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "mobile" => $mobile,
                "country" => $country_code,   //["US","IN","UK"],
                "ip_address" => $request->ip(),
            ],
            "payment_payload" => [
                "payment_ref_id" => $checkout_id,
                "request_amount" => $amount,
                "currency" => $currency,
                "notification_url" => "https://payment.ryzen-pay.com/api/payment/p7/notification",
                "return_url"  => "https://payment.ryzen-pay.com/payment/p7/payment-page/". $paymentRefId
            ],
            "risk_payload" => [
                "category_class" => "VIP",
                "device_fingerprint" => "NA"
            ],
            "custom_field1" => "1111",
            "custom_field2" => $checkout_id,
            "custom_field3" => $amount,
            "custom_field4" => "string",
            "custom_field5" => "string",
            "custom_field6" => "string",
            "custom_field7" => "string",
            "custom_field8" => "string",
            "custom_field9" => "string",
            "custom_field10" => "string"
            ]
        ];

        if($request->address != null){  $body['request_payload']['customer_payload']['address'] = $request->address;   }
        if($request->city != null){  $body['request_payload']['customer_payload']['city'] = $request->city; }
        if($request->state != null){  $body['request_payload']['customer_payload']['state'] = $request->state;   }
        if($request->postal_code != null){  $body['request_payload']['customer_payload']['postal_code'] = $request->postal_code;   }

        try{
            $client = new Client();

            $response = $client->post($this->baseURL. 'request/create',[
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $body
            ]);

            $responseBody = json_decode($response->getBody()->getContents(),true);
            Log::info("response body API: CARD/UPI mode ", $responseBody);
        }catch(RequestException $e) {
            Log::error("Error creating SecurePayZone Payment API :", (array)$e->getMessage());
        }

        if($responseBody['success'] == false){

            $messages = [];

            if (isset($responseBody['error_result']) && is_array($responseBody['error_result'])) {
                foreach ($responseBody['error_result'] as $error) {
                    if (isset($error['error_message'])) {
                        $messages[] = ucfirst($error['error_message']);
                    }
                }
            }

            $finalMessage = implode(' | ', $messages) ?: 'Unknown error';

            $checkoutData->status = "1";
            $checkoutData->save();

            return back()->with('error', $finalMessage);

        } else {

            $responsePayload = $responseBody['response_payload'];
            $requestPaymentMethod = $responsePayload['request_payment_method'];
            $responseSignatureReceived = $responsePayload['response_signature'] ?? null;
            $paymentResult = $responsePayload['payment_result'];

            $payment_id = $paymentResult['payment_id'];
            $payment_status = $paymentResult['payment_status'];
            $payment_amount = $paymentResult['payment_amount'];
            $payment_amountX100 = $paymentResult['payment_amount']*100;
            $payment_link = $paymentResult['payment_link'] ?? null;
            $payment_html = $paymentResult['payment_html'] ?? null;
            $payment_response_message = $paymentResult['payment_response_message'];


            if($responseSignatureReceived != null ) {
                if($requestPaymentMethod == "CARD"){
                    $plainText2 = "{$p7user->spz_authkey}||{$paymentRefId}||{$payment_id}||{$payment_status}||{$currency}||{$payment_amountX100}||{$p7user->spz_secretkey}";
                    $response_signature_generated  = hash('sha256', $plainText2);
                }else {
                    $plainText2 = "{$p7user->spz_authkey}||{$paymentRefId}||{$payment_id}||{$payment_status}||{$currency}||{$payment_amountX100}||{$payment_link}||{$payment_html}||{$p7user->spz_secretkey}";
                    $response_signature_generated  = hash('sha256', $plainText2);
                }

                if($response_signature_generated != $responseSignatureReceived){
                    Log::warning("Response signature didn't match", ["payment_method" => $requestPaymentMethod ,"responseSignatureReceived" => $responseSignatureReceived, "response_signature_generated" => $response_signature_generated]);
                }else{
                    Log::info("Response Signature Matched for checkout_id : ". $checkout_id);
                }
            }

            $trxn = Transaction::where('checkout_id', $checkout_id)->first() ?: new Transaction();

            $trxn->account_id= $checkoutData->accId;
            $trxn->currency= $currency; // currency in which we recieved in
            $trxn->amount= $payment_amount;  // amount in which we recieved in
            $trxn->checkout_id= $checkout_id;
            $trxn->payment_id= $payment_id;
            $trxn->payment_status= ucfirst(strtolower($payment_status));
            $trxn->description= $payment_response_message;
            $trxn->status= 'p7';

            $trxn->save();

            $checkoutData->status = "1";
            $checkoutData->save();
        }

        if($payment_link != null){
            return redirect()->to($payment_link);
        }else{
            return back()->with("success","Status: ". ucfirst(strtolower($payment_status)) ." | " . $payment_response_message);
        }

    }

    //H2H securepay zone
    public function h2hSecurePayZone(Request $request, $accId)
    {
        $date = Carbon::now()->subDays(30);
        CheckoutDetail::where('payment_partner','p7')->where('created_at', '<', $date)->delete();

        $p7user = PSevenPaymentMethod::where('accountId',$accId)->where('status',"1")->first() ;
        if(!$p7user){
            return response()->json(['error' => 'Unauthorized Access'],401);
        }

        $rules = [
            'currency' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:CARD',  //UPI,CARD,QR
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string|max:20',
            'country_code' => 'required|string|size:2',
            'return_url' => ['nullable', 'string', 'min:1'],
        ];

        // Conditional validation based on payment method
        if ($request->payment_method === 'CARD') {
            $rules = array_merge($rules, [
                'card_holder_name' => 'required|string|max:255',
                // 'card_type' => 'required|string|in:VISA,MASTERCARD,RUPAY', // adapt as needed
                'card_number' => 'required|digits_between:13,19',
                'expiry_month' => 'required|digits:2|between:1,12',
                'expiry_year' => 'required|digits:4|gte:' . date('Y'),
                'cvv' => 'required|digits_between:3,4',
            ]);
        }

        if ($request->payment_method === 'UPI') {
            $rules = array_merge($rules, [
                'upi_id' => 'required|string|max:255'
            ]);
        }

        $request->validate($rules,[
            'payment_method.required' => '*required field. available options: "CARD" ',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (CheckoutDetail::where('checkout_id', $uuid)->exists());

        $checkoutData = new CheckoutDetail();

        $checkoutData->accId = $accId;
        $checkoutData->payment_partner = 'p7';
        $checkoutData->amount = round($request->amount, 2);
        $checkoutData->currency = strtoupper($request->currency);
        $checkoutData->checkout_id = $uuid;
        $checkoutData->status = '0';

        $checkoutData->save();

        $country_code = $request->country_code;
        $payment_method = $request->payment_method;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $mobile = $request->mobile;
        $amount = round($checkoutData->amount,2);
        $currency= $checkoutData->currency;
        $paymentRefId = $checkoutData->checkout_id;

        if($payment_method == "UPI"){
            $payment_method_payload = [
                "upi_id"=> $request->upi_id
            ];

            $plainText = "{$p7user->spz_authkey}||{$paymentRefId}||{$currency}||{$p7user->spz_secretkey}";
            $request_signature  = hash('sha256', $plainText);
        }

        if($payment_method == "CARD"){
            $payment_method_payload = [
                "card_holder_name" => $request->card_holder_name,
                "card_type" => $this->getCardType($request->card_number),
                "card_number" => preg_replace('/\D/', '', $request->card_number),
                "expiry_month" => $request->expiry_month ,
                "expiry_year" => $request->expiry_year ,
                "cvv" => $request->cvv
            ];

            $plainText = "{$p7user->spz_authkey}||{$paymentRefId}||{$currency}||{$p7user->spz_secretkey}";
            $request_signature  = hash('sha256', $plainText);
        }

        $body = [
            "request_mode" => "payin",
            "request_payload"=> [
                "request_authkey"=> $p7user->spz_authkey,
                "request_flow"=> "direct",
                "request_payment_method"=> $payment_method, //[Card / UPI]
                "payment_method_payload"=> $payment_method_payload,
            "request_signature" => $request_signature,
            "customer_payload" => [
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "mobile" => $mobile,
                "country" => $country_code,   //["US","IN","UK"],
                "ip_address" => $request->ip(),
            ],
            "payment_payload" => [
                "payment_ref_id" => $paymentRefId,
                "request_amount" => $amount,
                "currency" => $currency,
                "notification_url" => "https://payment.ryzen-pay.com/api/payment/p7/notification",
                "return_url"  => $request->return_url ?? "https://payment.ryzen-pay.com/payment/p7/payment-page/". $paymentRefId
            ],
            "risk_payload" => [
                "category_class" => "VIP",
                "device_fingerprint" => "NA"
            ],
            "custom_field1" => "1111",
            "custom_field2" => $paymentRefId,
            "custom_field3" => $amount,
            "custom_field4" => "string",
            "custom_field5" => "string",
            "custom_field6" => "string",
            "custom_field7" => "string",
            "custom_field8" => "string",
            "custom_field9" => "string",
            "custom_field10" => "string"
            ]
        ];

        if($request->address != null){  $body['request_payload']['customer_payload']['address'] = $request->address;   }
        if($request->city != null){  $body['request_payload']['customer_payload']['city'] = $request->city; }
        if($request->state != null){  $body['request_payload']['customer_payload']['state'] = $request->state;   }
        if($request->postal_code != null){  $body['request_payload']['customer_payload']['postal_code'] = $request->postal_code;   }

        try{
            $client = new Client();

            $response = $client->post($this->baseURL. 'request/create',[
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $body
            ]);

            $responseBody = json_decode($response->getBody()->getContents(),true);
            Log::info(" h2h response body API: CARD/UPI mode ", $responseBody);
        }catch(RequestException $e) {
            Log::error(" h2h Error creating SecurePayZone Payment API :", (array)$e->getMessage());
        }

        if($responseBody['success'] == false){

            $messages = [];

            if (isset($responseBody['error_result']) && is_array($responseBody['error_result'])) {
                foreach ($responseBody['error_result'] as $error) {
                    if (isset($error['error_message'])) {
                        $messages[] = ucfirst($error['error_message']);
                    }
                }
            }

            $finalMessage = implode(' | ', $messages) ?: 'Unknown error';

            $checkoutData->status = "1";
            $checkoutData->save();

            return response()->json([
                "success" => false,
                "message" => $finalMessage ?? 'unexpected error',
            ],422);

        } else {

            $responsePayload = $responseBody['response_payload'];
            $requestPaymentMethod = $responsePayload['request_payment_method'];
            $responseSignatureReceived = $responsePayload['response_signature'] ?? null;
            $paymentResult = $responsePayload['payment_result'];

            $payment_id = $paymentResult['payment_id'];
            $payment_status = $paymentResult['payment_status'];
            $payment_amount = $paymentResult['payment_amount'];
            $payment_amountX100 = $paymentResult['payment_amount']*100;
            $payment_link = $paymentResult['payment_link'] ?? null;
            $payment_html = $paymentResult['payment_html'] ?? null;
            $payment_response_message = $paymentResult['payment_response_message'];


            if($responseSignatureReceived != null ) {
                if($requestPaymentMethod == "CARD"){
                    $plainText2 = "{$p7user->spz_authkey}||{$paymentRefId}||{$payment_id}||{$payment_status}||{$currency}||{$payment_amountX100}||{$p7user->spz_secretkey}";
                    $response_signature_generated  = hash('sha256', $plainText2);
                }else {
                    $plainText2 = "{$p7user->spz_authkey}||{$paymentRefId}||{$payment_id}||{$payment_status}||{$currency}||{$payment_amountX100}||{$payment_link}||{$payment_html}||{$p7user->spz_secretkey}";
                    $response_signature_generated  = hash('sha256', $plainText2);
                }

                if($response_signature_generated != $responseSignatureReceived){
                    Log::warning("Response signature didn't match", ["payment_method" => $requestPaymentMethod ,"responseSignatureReceived" => $responseSignatureReceived, "response_signature_generated" => $response_signature_generated]);
                }else{
                    Log::info("Response Signature Matched for checkout_id : ". $paymentRefId);
                }
            }

            $trxn = Transaction::where('checkout_id', $paymentRefId)->first() ?: new Transaction();

            $trxn->account_id= $checkoutData->accId;
            $trxn->currency= $currency; // currency in which we recieved in
            $trxn->amount= $payment_amount;  // amount in which we recieved in
            $trxn->checkout_id= $paymentRefId;
            $trxn->payment_id= $payment_id;
            $trxn->payment_status= ucfirst(strtolower($payment_status));
            $trxn->description= $payment_response_message;
            $trxn->status= 'p7';

            $trxn->save();

            $checkoutData->status = "1";
            $checkoutData->save();
        }

        if($payment_link != null){
            // return redirect()->to($payment_link);
            return response()->json([
                "success" => true,
                "amount" => round($request->amount, 2),
                "currency" => strtoupper($request->currency),
                "checkout_id" => $uuid,
                "status" => $trxn->payment_status,
                "link" => $payment_link
            ],200);
        }else{
            return response()->json([
                "success" => true,
                "amount" => round($request->amount, 2),
                "currency" => strtoupper($request->currency),
                "checkout_id" => $uuid,
                "status" => $trxn->payment_status,
                "description" => $payment_response_message,
            ],200);
        }
    }

    //api transaction status
    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = PSevenPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p7')->first();
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
                "created_at" => $transaction->created_at,
                "updated_at" => $transaction->updated_at
            ]
        ],200);
    }
}
