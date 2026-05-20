<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PEightPaymentMethod;
use App\Models\Transaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LuqaPaySubscriptionController extends Controller
{

    // protected $baseUrl = 'https://wallet.centrueprocessing.com'; //production
    protected $baseUrl = 'https://sandbox-wallet.centrueprocessing.com'; //sandbox
    /**
     * 1) Card Verification
     */
    public function verifyCard(Request $request ,$accId)
    {
        $checkaccId = PEightPaymentMethod::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkaccId) {
            return response()->json(['success' => false, 'error' => 'Unauthorized Account Id'], 401);
        }
        if($checkaccId->luqapay_subscription_apikey == null){
            return response()->json(['success' => false, 'error' => 'MID not assigned for this service'], 401);
        }

        $baseUrl  = rtrim($this->baseUrl, '/');
        $apiKey   = $checkaccId->luqapay_subscription_apikey;
        $endpoint = '/v2/credit_card/credit-card-verification';

        $validated = $request->validate([
            'currency'          => ['required','string','size:3'], // ISO 4217 e.g. EUR
            'number'            => ['required','string'],          // card number
            'expiryMonth'       => ['required','string'],          // e.g. "12"
            'expiryYear'        => ['required','string'],          // e.g. "2030"
            'cvv'               => ['required','string'],

            'email'             => ['required','email'],
            'birthday'          => ['required','date_format:Y-m-d'], // yyyy-MM-dd

            'billingFirstName'  => ['required','string'],
            'billingLastName'   => ['required','string'],
            'billingAddress1'   => ['required','string'],
            'billingCity'       => ['required','string'],
            'billingPostcode'   => ['required','string'],
            'billingCountry'    => ['required','string','size:2'],   // optional per doc
            'billingAddress'    => ['required','string'],
            'state'             => ['required','string'],

            'returnUrl'         => ['nullable','url'],    // required by doc; we will fallback if not provided
            'customerIp'        => ['nullable','ip'],     // required by doc; fallback from request
            'customerUserAgent' => ['nullable','string'], // required by doc; fallback from request UA
        ]);

        $referenceNo       = (string) Str::uuid();
        $returnUrl         = $validated['returnUrl'] ?? 'https://ryzen-pay.com'; // better: set a specific return URL
        $customerIp        = $validated['customerIp'] ?? $request->ip();
        $customerUserAgent = $validated['customerUserAgent'] ?? ($request->userAgent() ?? 'Unknown');

        $payload = [
            'apiKey'            => $apiKey,
            'currency'          => strtoupper($validated['currency']),
            'number'            => $validated['number'],
            'expiryMonth'       => $validated['expiryMonth'],
            'expiryYear'        => $validated['expiryYear'],
            'cvv'               => $validated['cvv'],
            'email'             => $validated['email'],
            'birthday'          => $validated['birthday'],
            'billingFirstName'  => $validated['billingFirstName'],
            'billingLastName'   => $validated['billingLastName'],
            'billingAddress1'   => $validated['billingAddress1'],
            'billingCity'       => $validated['billingCity'],
            'billingPostcode'   => $validated['billingPostcode'],
            'billingCountry'    => $validated['billingCountry'] ?? null,
            'referenceNo'       => $referenceNo,
            'returnUrl'         => $returnUrl,
            'customerIp'        => $customerIp,
            'customerUserAgent' => $customerUserAgent,
            'billingAddress'    => $validated['billingAddress'],
            'state'             => $validated['state'],
        ];

        $payload = array_filter($payload, fn($v) => !is_null($v));
        Log::channel('luqapay')->info("Subscription: card-verification payload: ", $payload);

        $client = new Client([
            'base_uri' => $baseUrl ,
            'timeout'  => 45,
        ]);

        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        ];

        try {
            $res  = $client->request('POST', $endpoint, [
                'headers' => $headers,
                'json'    => $payload,
            ]);

            $body = (string) $res->getBody();
            $json = json_decode($body, true) ?? [];

            Log::channel('luqapay')->info('Luqa Subscription card verification API response', [
                'http_status' => $res->getStatusCode(),
                'response'    => $json,
            ]);

            $form3dUrl      = $json['form3d'] ?? null; // 3DS form URL

            $trxn =  Transaction::where('checkout_id',$referenceNo)->where('status','p8')->first() ?: new Transaction();
            $trxn->account_id     = $checkaccId->accountId;
            $trxn->currency       = strtoupper($validated['currency']);
            $trxn->amount         = 0;
            $trxn->checkout_id    = $referenceNo;
            $trxn->payment_status = ucfirst(strtolower($json['status']));
            $trxn->description    = 'Message: '. $json['message']  .' | Status: ' . $json['status'];
            $trxn->status         = 'p8';
            $trxn->save();

            return response()->json([
                'success' => true,
                'message'  => $json['message'],
                'form3d'  => $form3dUrl,
            ], $res->getStatusCode());

        } catch (RequestException $e) {
            $status = optional($e->getResponse())->getStatusCode() ?: 500;
            $raw    = optional($e->getResponse())->getBody()?->getContents();

            Log::channel('luqapay')->error('Luqa verifyCard API error', [
                'endpoint' => $endpoint,
                'status'   => $status,
                'error'    => $e->getMessage(),
                'body'     => $raw,
                'payload'  => $payload,
            ]);

            return response()->json([
                'success' => false,
                'status'  => $status,
                'details' => $raw ?: $e->getMessage(),
            ], $status);
        }
    }

    /**
     * 2) Store Card (Tokenize) And Pay
     */
    public function storeCardAndCharge(Request $request, $accId)
    {
        $checkaccId = PEightPaymentMethod::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkaccId) {
            return response()->json(['success' => false ,'error' => 'Unauthorized Account Id'], 401);
        }
        if($checkaccId->luqapay_subscription_apikey == null){
            return response()->json(['success' => false ,'error' => 'MID not assigned for this service'], 401);
        }

        $baseUrl  = $this->baseUrl;
        $apiKey   = $checkaccId->luqapay_subscription_apikey;
        $endpoint = '/v2/checkout/initialize';

        $validated = $request->validate([
            'amount'             => ['required', 'numeric'],
            'currency'           => ['required', 'string', 'size:3'], // EUR
            'country'            => ['required', 'string', 'size:2'], // GB
            'dateOfBirth'        => ['required', 'date_format:Y-m-d'],
            'email'              => ['required', 'email'],
            'firstName'          => ['required', 'string'],
            'lastName'           => ['required', 'string'],
            'successRedirectUrl' => ['required', 'url'],
            'failRedirectUrl'    => ['required', 'url'],
            'address'            => ['required', 'string'],
            'city'               => ['required', 'string'],
            'postCode'           => ['required', 'string'],

            'storedCardId'       => ['nullable', 'string'],           // required if cardNumber, expDate, and pin are not present

            'cardNumber'         => ['nullable', 'string'], // required if storedCardId are not present
            'expDate'            => ['nullable', 'string'], // required if storedCardId are not present
            'pin'                => ['required', 'string'], // cvv,  required if storedCardId are not present

             // Customer info (required by docs)
            'ip'    => ['nullable', 'ip'],     // we'll fallback from request->ip()
            'agent' => ['nullable', 'string'], // we'll fallback from request->userAgent()
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());
        $referenceNo = $uuid;

        $payload = [
            'apiKey'               => $apiKey,
            'amount'               => number_format($validated['amount'],2),
            'country'              => strtoupper($validated['country']),
            'currency'             => strtoupper($validated['currency']),
            'dateOfBirth'          => $validated['dateOfBirth'],
            'defaultPaymentMethod' => 'CREDIT_CARD',
            'email'                => $validated['email'],
            'failRedirectUrl'      => $validated['failRedirectUrl'],
            'firstName'            => $validated['firstName'],
            'lastName'             => $validated['lastName'],
            'referenceNo'          => $referenceNo,
            'successRedirectUrl'   => $validated['successRedirectUrl'],
            'address'              => $validated['address'],
            'city'                 => $validated['city'],
            'postCode'             => $validated['postCode'],
            'language'             => "EN",
            'storeCard'            => (bool) true,
            'is3d'                 => (bool) true,
            'only3d'               => (bool) false,
        ];

        // Optional fields
        if (!empty($validated['storedCardId'])){
            $payload['storedCardId'] = $validated['storedCardId'];
        } elseif(!empty($validated['cardNumber']) && !empty($validated['expDate']) && !empty($validated['pin'])){
             $payload['cardNumber'] = $validated['cardNumber'];
             $payload['expDate']    = $validated['expDate'];
             $payload['pin']        = $validated['pin'];
        } else{
            return response()->json([
                "success" => false,
                "message" => "Either provide storedCardId or CardNumber and expDate"
            ],422);
        }

        $client = new Client([
            'base_uri' => $baseUrl ,
            'timeout'  => 30,
        ]);

        Log::channel('luqapay')->info("Subscription: checkout initialize api req payload: ", $payload);

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        try {
            $res  = $client->request('POST', $endpoint, [
                'headers' => $headers,
                'json'    => $payload,
            ]);

            $body = (string) $res->getBody();
            $json = json_decode($body, true) ?? [];

            Log::channel('luqapay')->info('Luqa storeCard initialize response', [
                'http_status' => $res->getStatusCode(),
                'response'    => $json,
            ]);

            $token = $json['token'] ?? null;

        } catch (RequestException $e) {
            $status = optional($e->getResponse())->getStatusCode() ?: 500;
            $raw    = optional($e->getResponse())->getBody()?->getContents();

            Log::channel('luqapay')->error('Luqa storeCard initialize API error', [
                'endpoint' => $endpoint,
                'status'   => $status,
                'error'    => $e->getMessage(),
                'body'     => $raw,
                'payload'  => $payload,
            ]);

            return response()->json([
                'success' => false,
                'error'   => 'Provider request failed',
                'details' => $raw ?: $e->getMessage(),
            ], $status);
        }

        // Doc-required customer info (don’t send localhost)
        $ip    = $validated['ip'] ?? $request->ip();
        $agent = $validated['agent'] ?? ($request->userAgent() ?? 'Unknown');
        if (in_array($ip, ['127.0.0.1', '::1', '172.0.0.1'], true)) {
            return response()->json([
                'success' => false,
                'error'   => 'Invalid IP for PAY request. Do not send localhost IP.',
            ], 422);
        }

        $payload2 = [
            'account' => [
                'pin' => $validated['pin'],
            ],
            'customerInfo' => [
                'ip'    => $ip,
                'agent' => $agent,
            ],
            'paymentMethod' => 'CREDIT_CARD',
        ];

        $useStoredCard = (bool) (isset($validated['storedCardId']) ?? false);

        // If NOT using stored card, docs require cardNumber + expDate
        if (!$useStoredCard) {
            if (empty($validated['cardNumber']) || empty($validated['expDate'])) {
                return response()->json([
                    'success' => false,
                    'error'   => 'cardNumber and expDate are required when not using stored card.',
                ], 422);
            }

            $payload2['account']['cardNumber'] = $validated['cardNumber'];
            $payload2['account']['expDate']    = $validated['expDate']; // "12-25"
        }

        $endpoint2 = '/v2/checkout/pay';
        $client2 = new Client([
            'base_uri' => $baseUrl . '/',
            'timeout'  => 45,
        ]);

        Log::channel('luqapay')->info("Subscription: Checkout Pay API Request: ", $payload2);

        $headers2 = [
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => $token,
        ];

        try {
            $res2 = $client2->request('POST', ltrim($endpoint2, '/'), [
                'headers' => $headers2,
                'json'    => $payload2,
            ]);

            $body2 = (string) $res2->getBody();
            $json2 = json_decode($body2, true) ?? [];

            Log::channel('luqapay')->info('Luqa PAY response', [
                'http_status' => $res2->getStatusCode(),
                'response'    => $json2,
            ]);

            $message2       = $json2['message'] ?? '';
            $status2        = $json2['status'] ?? '';       // WAITING, etc.
            $type2          = $json2['type'] ?? null;         // DIRECT
            $redirectUrl2   = $json2['redirectUrl'] ?? null;  // for 3DS redirect
            $transactionId2 = $json2['transactionId'] ?? null;
            $reference2     = $json2['reference'] ?? null;

            // extra.StoredCardId (note: docs show "StoredCardId" inside "extra")
            $storedCardId  = $json2['extra']['StoredCardId'] ?? '';

            $actualAmount   = $json2['actualAmount'] ?? null;
            $actualCurrency = $json2['actualCurrency'] ?? null;

            $trans = Transaction::where('checkout_id',$referenceNo)->where('status','p8')->first() ?: new Transaction();

            $trans->account_id     = $checkaccId->accountId;
            $trans->currency       = strtoupper($actualCurrency);
            $trans->amount         = round($actualAmount, 2);
            $trans->from_currency  = strtoupper($actualCurrency);
            $trans->from_amount    = round($actualAmount, 2);
            $trans->checkout_id    = $reference2;
            $trans->payment_id     = $transactionId2;
            $trans->payment_status = ucfirst(strtolower($status2));
            $trans->description    = 'Message: '. $message2  .' | Status: ' . $status2 .' | storedCardId: ' . $storedCardId;
            $trans->customer_details=  'Email: '. $validated['email'] ;
            $trans->status         = 'p8';

            $trans->save();

            return response()->json([
                'success' => true,
                'message'       => $message2,
                'status'=> $status2,
                'type'          => $type2,
                'redirectUrl'   => $redirectUrl2,
                'transactionId' => $transactionId2,
                'checkout_id'     => $reference2,
                'storedCardId'  => $storedCardId,
                'actualAmount'  => $actualAmount,
                'actualCurrency'=> $actualCurrency,
            ], $res2->getStatusCode());

        } catch (RequestException $e) {
            $statusCode = optional($e->getResponse())->getStatusCode() ?: 500;
            $raw        = optional($e->getResponse())->getBody()?->getContents();

            Log::channel('luqapay')->error('Luqa PAY request error', [
                'endpoint' => $endpoint,
                'status'   => $statusCode,
                'error'    => $e->getMessage(),
                'body'     => $raw,
                'payload'  => $payload,
            ]);

            return response()->json([
                'success' => false,
                'status'  => $statusCode,
                'error'   => 'Provider request failed',
                'details' => $raw ?: $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * 4) IPN Callback
     * Requirement: LOG EVERYTHING FIRST, THEN verify checksum.
     */
    public function ipn(Request $request)
    {
        Log::channel('luqapay')->info('Centrue IPN received all() : ',$request->all());
        $payload = $request->all();

        $referenceNo = $payload['referenceNo'];
        $email = $payload['email'] ?? 'N/A';
        $storedCardId = $request->storedCreditCardId ?? '';
        $trans = Transaction::where('checkout_id',$referenceNo)->where('status','p8')->first() ?: new Transaction();

        // $trans->account_id     = $checkaccId->accountId;
        $trans->currency       = strtoupper($request->currency);
        $trans->amount         = round($request->amount, 2);
        $trans->from_currency  = strtoupper($request->currency);
        $trans->from_amount    = round($request->amount, 2);
        $trans->checkout_id    = $referenceNo;
        $trans->payment_id     = $payload['transactionId'];
        $trans->payment_status = ucfirst(strtolower($request->status));
        $trans->description    = 'Message: '. $request->message  .' | Status: ' . $request->status .' | storedCardId: ' . $storedCardId;
        $trans->customer_details=  'Email: '. $email ;
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

            $body = [
                'message'        => (string) ($request->message ?? $payload['message'] ?? ''),
                'checkout_id'    => (string) $trans->checkout_id ?? '',
                'transaction_id' => (string) ($payload['transactionId'] ?? ''),
            ];

            if (!empty($trans->card_number)) {
                $body['card_number'] = (string) $trans->card_number;
            }
            if (!empty($storedCardId)) {
                $body['storedCardId'] = (string) $storedCardId;
            }

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p8/' . $trans->checkout_id, [
                'headers' => $headers,
                'json'    => $body,
                'timeout' => 15,
            ]);
            Log::channel('luqapay')->info("P8 Subscription forward OK response from client, status: {$resp->getStatusCode()}");

        } catch (RequestException $e) {
            Log::channel('luqapay')->warning("P8 Subscription forward api to client failed: " . $e->getMessage());
        } catch (\Exception $e){
            Log::channel('luqapay')->warning("P8 Subscription forward api to client failed: " . $e->getMessage(), [
                'sent_body' => $body ?? null,
            ]);
        }

        return response()->json(['ok' => true], 200);
    }
}
