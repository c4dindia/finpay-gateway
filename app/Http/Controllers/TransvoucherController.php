<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateP6PaymentStatusDispatcherJob;
use App\Models\PSixPaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransvoucherController extends Controller
{
    protected $transVoucherApiKey;
    protected $transVoucherSecretKey;
    protected $transvoucherApiUrl;

    public function __construct()
    {
        // $this->transVoucherApiKey = "tvc_GOOH5fA9HlZU9SvROEnZnZkYbtew01wc"; //sandbox
        // $this->transVoucherSecretKey = "tvcs_LneQa3QgdKLMJoy5QenI5NnE0JosbUUsQsYHmvviUsMqqT2I"; //sandbox
        // $this->transvoucherApiUrl = "https://sandbox-api.transvoucher.com"; //sandbox

        //secret key for lifeup inc tvcs_K8FmThwV4AtrOOhuvVNzvNSEvAkMdCj6CB839pR8BYsBRffT

        $this->transVoucherApiKey = config('app.transVoucherApiKey');    //production
        $this->transVoucherSecretKey= config('app.transVoucherSecretKey');   //production
        $this->transvoucherApiUrl = "https://api.trans-voucher.com"; //production
    }

    public function transvoucherCheckoutDetail(Request $request, $accId)
    {
        Log::info('transvoucher create checkout request data: ', $request->all());

        // simple account check
        $checkaccId = PSixPaymentMethod::where('accountId', $accId)
            ->where('status', '1')
            ->first();

        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized Account Id'], 401);
        }

        $rules = [
            'amount'        => 'required|numeric|min:0.01',
            'currency'      => 'nullable|in:AED,ARS,AUD,BDT,BGN,BRL,CAD,CHF,CLP,CNY,COP,CZK,DKK,EGP,EUR,GBP,GHS,HKD,HRK,HUF,IDR,ILS,INR,ISK,JPY,KES,KRW,KWD,MAD,MXN,MYR,NGN,NOK,NZD,PEN,PHP,PKR,PLN,QAR,RON,RUB,SAR,SEK,SGD,THB,TRY,TWD,USD,VND,ZAR',
            'redirect_url'  => 'nullable|url',
            'success_url'   => 'nullable|url',
            'cancel_url'    => 'nullable|url',

            // customer_details is OPTIONAL now
            'customer_details'                  => 'nullable|array',
            'customer_details.id'               => 'nullable|string|max:255',
            'customer_details.first_name'       => 'nullable|string|max:255',
            'customer_details.middle_name'      => 'nullable|string|max:255',
            'customer_details.last_name'        => 'nullable|string|max:255',
            'customer_details.full_name'        => 'nullable|string|max:255',
            'customer_details.email'            => 'nullable|email',
            'customer_details.phone'            => 'nullable|string|max:50',
            'customer_details.date_of_birth'    => 'nullable|date_format:Y-m-d',
            'customer_details.country_of_residence' => 'nullable|string|size:2',
            'customer_details.state_of_residence'   => 'nullable|string|max:100|required_if:customer_details.country_of_residence,US',

            // card billing prefill (all optional)
            'customer_details.card_country_code' => 'nullable|string|size:2',
            'customer_details.card_state_code'   => 'nullable|string|max:100',
            'customer_details.card_city'         => 'nullable|string|max:255',
            'customer_details.card_post_code'    => 'nullable|string|max:50',
            'customer_details.card_street'       => 'nullable|string|max:255',

            'lang'           => 'nullable|in:en,es,fr,de,it,pt,ru,zh,ja,ko,tr',
            'customer_email' => 'nullable|email',
            'is_price_dynamic' => 'nullable|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::warning("Validation Failed for creating transvoucher checkout");
            return response()->json([
                'error'    => 'Validation failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        $amount           = (float) $request->amount;
        $currency         = strtoupper($request->currency ?? 'USD'); // default USD
        $lang             = $request->filled('lang') ? $request->lang : 'en';
        $is_price_dynamic = $request->boolean('is_price_dynamic', false);

        $payload = [
            'amount'                => $amount,
            'currency'              => $currency,
            'title'                 => $checkaccId->salesChannel,
            'lang'                  => $lang,
            'default_payment_method'=> "card", // "apple-pay", "google-pay", "card"
            'payment_method_forced' => true,
            // 'redirect_url'          => $request->input('redirect_url', url('/p6/thank-you-page')),
            'cancel_on_first_fail'  => true,
            'is_price_dynamic'      => $is_price_dynamic,
        ];

        // include optional URLs if present
        foreach (['success_url', 'cancel_url'] as $u) {
            if ($request->filled($u)) {
                $payload[$u] = $request->input($u);
            }
        }

        if ($request->filled('redirect_url')) {
            $payload['redirect_url'] = $request->input('redirect_url');
        }

        // customer_details sanitise
        $rawCustomer    = $request->input('customer_details', []);
        $customerEmail  = $request->input('customer_email');
        $cleanCustomer  = [];

        if (!empty($rawCustomer) || $customerEmail) {

            if (is_array($rawCustomer)) {
                $cleanCustomer = $rawCustomer;

                // trim name fields
                foreach (['first_name', 'last_name', 'full_name'] as $nameKey) {
                    if (isset($cleanCustomer[$nameKey])) {
                        $cleanCustomer[$nameKey] = trim((string) $cleanCustomer[$nameKey]);
                    }
                }

                // drop first_name/last_name if length < 2
                if (isset($cleanCustomer['first_name']) && mb_strlen($cleanCustomer['first_name']) < 2) {
                    unset($cleanCustomer['first_name']);
                }
                if (isset($cleanCustomer['last_name']) && mb_strlen($cleanCustomer['last_name']) < 2) {
                    unset($cleanCustomer['last_name']);
                }

                // if full_name is present but < 2 chars, drop it
                if (isset($cleanCustomer['full_name']) && mb_strlen($cleanCustomer['full_name']) < 2) {
                    unset($cleanCustomer['full_name']);
                }

                // if non-US, drop state fields as per docs
                if (isset($cleanCustomer['country_of_residence']) &&
                    strtoupper($cleanCustomer['country_of_residence']) !== 'US') {
                    unset($cleanCustomer['state_of_residence']);
                }
                if (isset($cleanCustomer['card_country_code']) &&
                    strtoupper($cleanCustomer['card_country_code']) !== 'US') {
                    unset($cleanCustomer['card_state_code']);
                }
            }

            // fallback email if none inside customer_details
            if ($customerEmail && empty($cleanCustomer['email'])) {
                $cleanCustomer['email'] = $customerEmail;
            }

            // prefer customer_details.lang over top-level lang if present
            if (!empty($cleanCustomer['lang'])) {
                $payload['lang'] = $cleanCustomer['lang'];
            }

            // drop null/empty values
            $cleanCustomer = array_filter(
                $cleanCustomer,
                fn($v) => !is_null($v) && $v !== ''
            );

            if (!empty($cleanCustomer)) {
                $payload['customer_details'] = $cleanCustomer;
            }
        }

        // embed something useful by default
        $payload['metadata'] = [
            'accountId' => (string) $accId,
            'source'    => 'laravel_backend',
        ];

        try {
            $client = new Client();
            $response = $client->post($this->transvoucherApiUrl . '/v1.0/payment/create', [
                'json'    => $payload,
                'headers' => [
                    'X-API-Key'     => $checkaccId->apiKey,
                    'X-API-Secret'  => $checkaccId->secretKey,
                    'environment'   => 'production',
                    'Content-Type'  => 'application/json',
                ],
                'timeout' => 30,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info("response body after calling create payment transvoucher API:", $responseBody);

            if (!($responseBody['success'] ?? false)) {
                Log::warning('TransVoucher API returned success=false', $responseBody);
                return response()->json([
                    'success' => false,
                    'errors'  => $responseBody['errors'] ?? ['message' => 'Provider returned success=false'],
                ], 502);
            }

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody  = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];
            Log::error("Error in TransVoucher API: ", $errorBody);

            return response()->json([
                'success' => false,
                'errors'  => $errorBody['errors'] ?? $errorBody ?? ['message' => 'Unknown error'],
            ], $statusCode);
        }

        $data = $responseBody['data'] ?? [];

        $transaction_id  = $data['transaction_id']   ?? null;
        $payment_link_id = $data['payment_link_id']  ?? null;
        $paylink         = $data['payment_url']      ?? null;
        $expires_at      = $data['expires_at']       ?? null;
        $status          = $data['status']           ?? null;
        $respAmount      = $data['amount']           ?? $amount;
        $respCurrency    = strtoupper($data['currency'] ?? $currency);

        return response()->json([
            'success'     => true,
            'amount'      => number_format((float)$respAmount, 2, '.', ''),
            'currency'    => $respCurrency,
            'checkout_id' => $payment_link_id,
            'payment_id'  => $transaction_id,
            'expires_at'  => $expires_at,
            'status'      => $status,
            'link'        => $paylink,
        ], 200);
    }

    public function transvoucherGetPaymentStatus($accId , $checkout_id)
    {
        $checkaccId = PSixPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('status','p6')->latest('id')->first();
        if($transaction == null){
            return response()->json(['error' => 'Unauthorized Checkout Id'],401);
        }

        return response()->json([
            'data' => [
                "account_id" => $transaction->account_id,
                "currency" => $transaction->currency,
                "amount" => $transaction->amount,
                "from_currency" => $transaction->from_currency,
                "from_amount" => $transaction->from_amount,
                "net_amount" =>  $transaction->net_amount,
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description ?? null,
                "customer_data" => $transaction->customer_details,
                "created_at" => $transaction->created_at,
            ]
        ],200);
    }

    public function thankYouPage($checkout_id)
    {
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('status','p6')->first();
        if (! $transaction) {
            return response()->make('
                <!doctype html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Transaction Not Found</title>
                    <style>
                        * {
                            box-sizing: border-box;
                            margin: 0;
                            padding: 0;
                        }
                        body {
                            height: 100vh;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background: linear-gradient(135deg, #11A9FF15, #2FBF7115),
                                        radial-gradient(circle at 30% 20%, #11A9FF20, transparent 70%),
                                        #f6fbff;
                            font-family: "Segoe UI", Arial, sans-serif;
                            padding: 20px;
                        }
                        .card {
                            background: rgba(255, 255, 255, 0.85);
                            backdrop-filter: blur(12px);
                            border-radius: 18px;
                            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
                            padding: 40px 30px;
                            max-width: 420px;
                            width: 100%;
                            text-align: center;
                            animation: floaty 6s ease-in-out infinite;
                        }
                        @keyframes floaty {
                            0%, 100% { transform: translateY(0px); }
                            50%      { transform: translateY(-6px); }
                        }
                        .icon {
                            font-size: 60px;
                            color: #11A9FF;
                            margin-bottom: 15px;
                        }
                        h1 {
                            font-size: 1.7rem;
                            color: #333;
                            margin-bottom: 10px;
                        }
                        p {
                            color: #555;
                            font-size: 1rem;
                            margin-bottom: 15px;
                            line-height: 1.6;
                        }

                    </style>
                </head>
                <body>

                    <div class="card">
                        <div class="icon">⚠️</div>
                        <h1>Transaction Not Found</h1>
                        <p>It may be still processing.<br>Please wait a moment until the transaction completes.</p>

                    </div>

                </body>
                </html>
            ');
        }
        return view('payment.transvoucher.thankyoupage',compact('transaction'));
    }

    public function webhookNotification(Request $request)
    {
        Log::info("Webhook hit: TransVoucher");

        $payload = $request->getContent();
        Log::info("Payload: " . $payload);

        $webhookData = json_decode($payload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Invalid JSON in webhook');
            return response()->json(['error' => 'Bad Request'], 400);
        }

        Log::info("Event Received: " . ($webhookData['event'] ?? 'unknown'));
        $event = $webhookData['event'] ?? null;

        $data = $webhookData['data'] ?? [];
        $transactionData = $data['transaction'] ?? null;
        $salesChannelData = $data['sales_channel'] ?? null;
        $metadata         = $data['metadata'] ?? [];
        $customer_details = $data['customer_details'] ?? null;
        $failReason       = $data['fail_reason'] ?? null; // present on failed events in your sample

        if (!$transactionData || empty($transactionData['id'])) {
            Log::warning('Missing transaction payload or id');
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $checkoutId     = (string)$data['payment_link_id']; //(string)($transactionData['id']);                    // our checkout_id
        $referenceId    = $transactionData['reference_id'] ?? null;            // our payment_id
        $transactionId  = $transactionData['id'] ?? null;
        $statusRaw      = strtolower((string)($transactionData['status'] ?? '')); // pending|processing|completed|failed|cancelled|expired
        $fiatCurrency   = strtoupper((string)($transactionData['fiat_currency'] ?? 'USD'));

        $fiatAmountStr  = (string)($transactionData['fiat_total_amount'] ?? $transactionData['fiat_base_amount'] ?? '0');
        $commodityCur   = isset($transactionData['commodity']) ? strtoupper((string)$transactionData['commodity']) : null; // e.g., USDT
        $commodityAmtStr= isset($transactionData['commodity_amount']) ? (string)$transactionData['commodity_amount'] : null; // string "10.000000"
        $network        = $transactionData['network'] ?? null;                 // e.g., polygon

        $fromCurrency     = $fiatCurrency;
        $fromAmount      = number_format((float)$fiatAmountStr, 8, '.', '');

        $currency  = $commodityCur;
        $amount    = number_format((float)($commodityAmtStr ?? $fiatAmountStr), 8, '.', '');

        $descParts = [];
        $descParts[] = "event: {$event}";
        if ($network)     $descParts[] = "network: {$network}";
        if ($failReason)  $descParts[] = "fail_reason: {$failReason}";

        $detail = PSixPaymentMethod::where('salesChannel', $salesChannelData['name'])->first();

        switch($webhookData['event']) {
            case 'payment_intent.created':
                Log::info("Handling event: payment_intent.created for {$checkoutId}");

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();

                    $trans->account_id     = $metadata['accountId'] ?? $detail->accountId ?? null;
                    $trans->currency       = $currency;
                    $trans->amount         = $amount;
                    $trans->from_currency  = $fromCurrency;
                    $trans->from_amount    = $fromAmount;
                    $trans->checkout_id    = $checkoutId;
                    $trans->payment_id     = $transactionId;
                    $trans->payment_status = ucfirst($statusRaw);
                    $trans->description    = 'Network : '. $network;
                    $trans->status         = 'p6';

                    $trans->save();

                break;

            case 'payment_intent.processing':
                // Handle processing event
                Log::info("Handling event: payment_intent.processing for {$checkoutId}");

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();

                    $trans->account_id     = $detail->accountId;
                    $trans->currency       = $currency;
                    $trans->amount         = $amount;
                    $trans->from_currency  = $fromCurrency;
                    $trans->from_amount    = $fromAmount;
                    $trans->checkout_id    = $checkoutId;
                    $trans->payment_id     = $transactionId;
                    $trans->payment_status = ucfirst($statusRaw);
                    $trans->description    = 'Network : '. $network;
                    $trans->status         = 'p6';

                    $trans->save();
                break;

            case 'payment_intent.attempting':
                // Handle processing event
                Log::info("Handling event: payment_intent.attempting for {$checkoutId}");

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();
                    $trans->payment_status = ucfirst($statusRaw);

                    $trans->save();
                break;

            case 'payment_intent.succeeded':
                // Handle succeeded event
                Log::info("Handling event: payment_intent.succeeded for {$checkoutId}");
                    $name = filled($customer_details['full_name'] ?? null) ? trim($customer_details['full_name']) : null;
                    $email = filled($customer_details['email'] ?? null) && filter_var($customer_details['email'], FILTER_VALIDATE_EMAIL) ? $customer_details['email'] : null;
                    $phone = filled($customer_details['phone'] ?? null) ? preg_replace('/[^0-9+\s().-]/', '', $customer_details['phone']) : null;
                    $blockchainHashTrxn = filled($data['blockchain_tx_hash'] ?? null) ? trim($data['blockchain_tx_hash']) : null;
                    $settled_amount = $data["settled_amount"] ?? null;

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();

                    $trans->account_id     = $detail->accountId;
                    $trans->currency       = $currency;
                    $trans->amount         = $amount;
                    $trans->settled_amount = $settled_amount;
                    $trans->from_currency  = $fromCurrency;
                    $trans->from_amount    = $fromAmount;
                    $trans->checkout_id    = $checkoutId;
                    $trans->payment_id     = $transactionId;
                    $trans->payment_status = ucfirst($statusRaw);
                    $trans->description    = 'Network : '. $network;
                    $trans->customer_details= Str::of("Name: {$name} , Email: {$email} , Phone: {$phone}")->squish()->trim();
                    $trans->transvoucher_blockchainHashTrxn = $blockchainHashTrxn;
                    $trans->status         = 'p6';

                    $trans->save();

                break;

            case 'payment_intent.expired':
                // Handle expired event
                Log::info("Handling event: payment_intent.expired for {$checkoutId}");
                    $name = filled($customer_details['full_name'] ?? null) ? trim($customer_details['full_name']) : null;
                    $email = filled($customer_details['email'] ?? null) && filter_var($customer_details['email'], FILTER_VALIDATE_EMAIL) ? $customer_details['email'] : null;
                    $phone = filled($customer_details['phone'] ?? null) ? preg_replace('/[^0-9+\s().-]/', '', $customer_details['phone']) : null;

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();

                    $trans->account_id     = $detail->accountId;
                    $trans->currency       = $currency;
                    $trans->amount         = $amount;
                    $trans->from_currency  = $fromCurrency;
                    $trans->from_amount    = $fromAmount;
                    $trans->checkout_id    = $checkoutId;
                    $trans->payment_id     = $transactionId;
                    $trans->payment_status = ucfirst($statusRaw);
                    $trans->description    = "Expired. " . $trans->description;
                    $trans->customer_details= Str::of("Name: {$name} , Email: {$email} , Phone: {$phone}")->squish()->trim();
                    $trans->status         = 'p6';

                    $trans->save();

                break;

            case 'payment_intent.failed':
                // Handle failed event
                Log::info("Handling event: payment_intent.failed for {$checkoutId}, reason: {$failReason}");
                    $name = filled($customer_details['full_name'] ?? null) ? trim($customer_details['full_name']) : null;
                    $email = filled($customer_details['email'] ?? null) && filter_var($customer_details['email'], FILTER_VALIDATE_EMAIL) ? $customer_details['email'] : null;
                    $phone = filled($customer_details['phone'] ?? null) ? preg_replace('/[^0-9+\s().-]/', '', $customer_details['phone']) : null;

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();

                    $trans->account_id     = $detail->accountId;
                    $trans->currency       = $currency;
                    $trans->amount         = $amount;
                    $trans->from_currency  = $fromCurrency;
                    $trans->from_amount    = $fromAmount;
                    $trans->checkout_id    = $checkoutId;
                    $trans->payment_id     = $transactionId;
                    $trans->payment_status = ucfirst($statusRaw);
                    $trans->description    = $failReason ?? 'Network : '. $network;
                    $trans->customer_details= Str::of("Name: {$name} , Email: {$email} , Phone: {$phone}")->squish()->trim();
                    $trans->status         = 'p6';

                    $trans->save();
                break;

            case 'payment_intent.cancelled':
                // Handle cancelled event
                Log::info("Handling event: payment_intent.cancelled for {$checkoutId}");
                    $name = filled($customer_details['full_name'] ?? null) ? trim($customer_details['full_name']) : null;
                    $email = filled($customer_details['email'] ?? null) && filter_var($customer_details['email'], FILTER_VALIDATE_EMAIL) ? $customer_details['email'] : null;
                    $phone = filled($customer_details['phone'] ?? null) ? preg_replace('/[^0-9+\s().-]/', '', $customer_details['phone']) : null;

                    $trans = Transaction::where('checkout_id',$checkoutId)->where('status','p6')->first() ?: new Transaction();

                    $trans->account_id     = $detail->accountId;
                    $trans->currency       = $currency;
                    $trans->amount         = $amount;
                    $trans->from_currency  = $fromCurrency;
                    $trans->from_amount    = $fromAmount;
                    $trans->checkout_id    = $checkoutId;
                    $trans->payment_id     = $transactionId;
                    $trans->payment_status = ucfirst($statusRaw);
                    $trans->description    = 'Network : '. $network;
                    $trans->customer_details= Str::of("Name: {$name} , Email: {$email} , Phone: {$phone}")->squish()->trim();
                    $trans->status         = 'p6';

                    $trans->save();

                break;

            default:
                Log::warning("Unhandled webhook event: {$event}");
                break;
        }

         // --- Signature verification ---
        // $signatureHeader = $request->header('x-webhook-signature'); // e.g. "sha256=<hex>"
        // if (!$signatureHeader || !str_starts_with($signatureHeader, 'sha256=')) {
        //     Log::warning('Missing or invalid signature header');
        //     // return response()->json(['error' => 'Invalid signature'], 401);
        // }
        // $providedSignature = substr($signatureHeader, 7);
        // $secret = $detail->secretKey ?? null;
        // $computedSignature = hash_hmac('sha256', $payload, $secret);

        // // Constant-time compare
        // if (!hash_equals($computedSignature, $providedSignature)) {
        //     Log::warning('Signature mismatch');
        //     // return response()->json(['error' => 'Signature verification failed'], 403);
        // }

        $webhookData = json_decode($payload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Invalid JSON in webhook');
            return response()->json(['error' => 'Bad Request'], 400);
        }

        try {
            if ($detail && $detail->redirect_url && $detail->b_token) {
                $headers = [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $detail->b_token,
                ];

                $webhook = new Client();
                $resp = $webhook->get($detail->redirect_url . '/api/RyzenPay/p6/' . $trans->checkout_id, [
                    'headers' => $headers,
                    'timeout' => 15,
                ]);
                Log::info("P6 forward OK response from client, status: {$resp->getStatusCode()}");
            } else {
                Log::info(" missing PSixPaymentMethod details.");
            }
        } catch (RequestException $e) {
            Log::warning("Downstream forward failed: " . $e->getMessage());
        } catch(\Exception $e){
            Log::warning("Exception while sending our client webhook notification: " . $e->getMessage());
        }

        return response()->json(['status' => 'received'], 200);
    }

    // Updates All Transvoucher Pending transactions
    // public function updatePaymentStatus($status)
    // {
    //     $status = ucFirst($status);
    //     $transactions = Transaction::where('status', 'p6')->where('created_at', '>=', Carbon::now()->subDays(3))->where('payment_status', $status)->orderBy('id','desc')->limit(500)->get();
    //     $client = new Client();
    //     $i=0;

    //     foreach ($transactions as $trxn) {
    //         try {
    //             $checkaccId = PSixPaymentMethod::where('accountId', $trxn->account_id)->where('status', '1')->first();

    //             if (!$checkaccId) {
    //                 Log::warning("No active PSixPaymentMethod found for account ID: {$trxn->account_id}");
    //                 continue;
    //             }

    //             $response = $client->get($this->transvoucherApiUrl . '/v1.0/payment/status/' . $trxn->payment_id, [
    //                 'headers' => [
    //                     'X-API-Key'     => $checkaccId->apiKey,
    //                     'X-API-Secret'  => $checkaccId->secretKey,
    //                     'environment'   => 'production',
    //                     'Content-Type'  => 'application/json',
    //                 ]
    //             ]);

    //             $responseBody = json_decode($response->getBody()->getContents(), true);
    //             Log::info("TransVoucher response for transaction id {$trxn->payment_id}:", $responseBody);

    //             if (!($responseBody['success'] ?? false)) {
    //                 Log::warning("TransVoucher API returned success=false for transaction id {$trxn->payment_id}", $responseBody);
    //                 continue;
    //             }

    //             $data = $responseBody['data'] ?? [];

    //             if (!empty($data['status'])) {
    //                 $trxn->payment_status = ucfirst($data['status']);

    //                 if(in_array($trxn->payment_status, ["Completed","Failed","Cancelled"])){
    //                     $trxn->transvoucher_blockchainHashTrxn = $data['blockchain_tx_hash'] ?? null;
    //                     $trxn->transvoucher_card_brand = $data['payment_method']['card_brand'] ?? null;
    //                     $trxn->settled_amount = $data['settled_amount'] ?? null;
    //                 }

    //                 $trxn->save();
    //                 Log::info("Transaction {$trxn->id} status updated to {$trxn->payment_status}");
    //             } else {
    //                 Log::warning("No status found in response for transaction {$trxn->id}", $responseBody);
    //             }

    //         } catch (RequestException $e) {
    //             $errorBody = $e->getResponse()
    //                 ? json_decode($e->getResponse()->getBody(), true)
    //                 : ['error' => $e->getMessage()];

    //             Log::error("Error calling TransVoucher API for transaction {$trxn->id}: ", (array) $errorBody);
    //         } catch (\Exception $e) {
    //             Log::error("Unexpected error for transaction {$trxn->id}: " . $e->getMessage());
    //         }

    //         if(ucFirst(strtolower($status)) != ucFirst(strtolower($trxn->payment_status)) )
    //         {
    //             try {
    //                 if ($checkaccId && $checkaccId->redirect_url && $checkaccId->b_token) {
    //                     $headers = [
    //                         'Content-Type'  => 'application/json',
    //                         'Authorization' => $checkaccId->b_token,
    //                     ];

    //                     $webhook = new Client();
    //                     $resp = $webhook->get($checkaccId->redirect_url . '/api/RyzenPay/p6/' . $trxn->checkout_id, [
    //                         'headers' => $headers,
    //                         'timeout' => 15,
    //                     ]);
    //                     Log::info("P6 forward OK response from client, status: {$resp->getStatusCode()}");
    //                 } else {
    //                     Log::info(" missing PSixPaymentMethod details.");
    //                 }
    //             } catch (RequestException $e) {
    //                 Log::warning("Downstream forward failed: " . $e->getMessage());
    //             }
    //         }
    //         $i++;
    //     }

    //     return response()->json([
    //         "success" => true,
    //         "message" => "All ".$i." statuses have been updated upto last 3 days for ". $status
    //     ]);
    // }

    public function updatePaymentStatus($status)
    {
        UpdateP6PaymentStatusDispatcherJob::dispatch($status);

        return response()->json([
            "success" => true,
            "message" => "Payment status update queued for processing for status {$status}"
        ]);
    }

    //update Transvoucher trxn status by id
    public function updateP6TrxnStatus($trxn_id)
    {
        $trxn = Transaction::where('status', 'p6')->where('payment_id', $trxn_id)->first();
        $oldStatus = ucfirst( strtolower($trxn->payment_status) );
            try {
                $client = new Client();
                $checkaccId = PSixPaymentMethod::where('accountId', $trxn->account_id)->first();
                $response = $client->get($this->transvoucherApiUrl . '/v1.0/payment/status/' . $trxn->payment_id, [
                    'headers' => [
                        'X-API-Key'     => $checkaccId->apiKey,
                        'X-API-Secret'  => $checkaccId->secretKey,
                        'environment'   => 'production',
                        'Content-Type'  => 'application/json',
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);
                Log::info("TransVoucher response for transaction id {$trxn->payment_id}:", $responseBody);

                if (!($responseBody['success'] ?? false)) {
                    Log::warning("TransVoucher API returned success=false for transaction id {$trxn->payment_id}", $responseBody);
                    return back()->with("info","Try again later !");
                }

                $data = $responseBody['data'] ?? [];

                if (!empty($data['status'])) {
                    $trxn->payment_status = ucfirst($data['status']);

                    if(in_array($trxn->payment_status, ["Completed","Failed","Cancelled"])){
                        $trxn->transvoucher_blockchainHashTrxn = $data['blockchain_tx_hash'] ?? null;
                        $trxn->transvoucher_card_brand = $data['payment_method']['card_brand'] ?? null;
                    }

                    $trxn->save();

                    if($trxn->payment_status == 'Completed'){
                        $trxn->settled_amount = $data['settled_amount'] ?? $trxn->settled_amount ?? null;
                    }

                    Log::info("Transaction {$trxn->payment_id} status updated to {$trxn->payment_status}");
                } else {
                    Log::warning("No status found in response for transaction {$trxn->id}", $responseBody);
                }

            } catch (RequestException $e) {
                $errorBody = $e->getResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : ['error' => $e->getMessage()];

                Log::error("Error calling TransVoucher API for transaction {$trxn->id}: ", (array) $errorBody);
                return back()->with("error","Try again later");
            } catch (\Exception $e) {
                Log::error("Unexpected error for transaction {$trxn->id}: " . $e->getMessage());
                return back()->with("error","Try again later.");
            }

            if( $oldStatus != ucfirst( strtolower($trxn->payment_status) ) )
            {
                try {
                    if ($checkaccId && $checkaccId->redirect_url && $checkaccId->b_token) {
                        $headers = [
                            'Content-Type'  => 'application/json',
                            'Authorization' => $checkaccId->b_token,
                        ];
                        $webhook = new Client();
                        $resp = $webhook->get($checkaccId->redirect_url . '/api/RyzenPay/p6/' . $trxn->checkout_id, [
                            'headers' => $headers,
                            'timeout' => 15,
                        ]);
                        Log::info("P6 forward OK response to client, status: {$resp->getStatusCode()}");
                    } else {
                        Log::info(" missing PSixPaymentMethod details.");
                    }
                } catch (RequestException $e) {
                    Log::warning("Downstream forward failed: " . $e->getMessage());
                }
            }

        return back()->with("success","Updated");
    }

    //generate Transvoucher payment Link
    public function openPaymentLink($accId)
    {
        // simple account check
        $checkaccId = PSixPaymentMethod::where('accountId', $accId)->where('status', '1')->first();

        if ($checkaccId == null) {
            return response()->json(['error' => 'Unauthorized '], 401);
        }

        $amount   = (float) 20;
        $currency = 'USD'; // default USD  // 'amount'       => $amount,
        $lang     = 'en';
        $is_price_dynamic = true ;

        $payload = [
            'currency'     => 'USD',
            'title'        => $checkaccId->salesChannel,
            'lang'         => $lang,
            'default_payment_method'=> "card", // "apple-pay", "google-pay"
            'payment_method_forced' => true,
            // 'redirect_url' => url('/p6/thank-you-page'),
            'cancel_on_first_fail' => true,
            'is_price_dynamic'     => $is_price_dynamic
        ];

        // embed something useful by default
        $payload['metadata'] = [
            'accountId' => (string) $accId,
            'source'    => 'generated open payment link',
        ];

        try {
            $client = new Client();
            $response = $client->post($this->transvoucherApiUrl . '/v1.0/payment/create', [
                'json' => $payload,
                'headers' => [
                    'X-API-Key'     => $checkaccId->apiKey,
                    'X-API-Secret'  => $checkaccId->secretKey,
                    'environment'   => 'production',
                    'Content-Type'  => 'application/json',
                ],
                'timeout' => 30,
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info("response body after calling create payment transvoucher API:", $responseBody);

            if (!($responseBody['success'] ?? false)) {
                Log::warning('TransVoucher API returned success=false', $responseBody);
                return response()->json([
                    'success' => false,
                    'errors'  => $responseBody['errors'] ?? ['message' => 'Provider returned success=false'],
                ], 502);
            }

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody(), true) : [];
            Log::error("Error in TransVoucher API: ", $errorBody);

            return response()->json([
                'success' => false,
                'errors' => $errorBody['errors'] ?? ['message' => 'Unknown error'],
            ], $statusCode);
        }

        $data = $responseBody['data'] ?? [];

        $transaction_id    = $data['transaction_id'] ?? null;
        $payment_link_id   = $data['payment_link_id'] ?? null;
        $paylink        = $data['payment_url'] ?? null;
        $expires_at     = $data['expires_at'] ?? null;
        $status         = $data['status'] ?? null;
        $respAmount     = $data['amount'] ?? $amount;
        $respCurrency   = strtoupper($data['currency'] ?? $currency);

        return response()->json([
            'success'       => true,
            'amount'        => (float) $respAmount,
            'currency'      => $respCurrency,
            'checkout_id'   => $payment_link_id,
            'payment_id'    => $transaction_id,
            'expires_at'    => $expires_at,
            'status'        => $status,
            'link'          => $paylink,
        ], 200);
    }

    public function showPaymentLinkGeneratorPage($accId)
    {
        return view('payment.transvoucher.payment-link', compact('accId'));
    }
}
