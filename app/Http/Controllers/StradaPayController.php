<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\PFourPaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StradaPayController extends Controller
{
    protected $apiUrl = 'https://restapi.stradapays.com/api/process/request';
    // protected $midsecret = '67b5b626-601e-447f-8625-aa9e6e88e146'; //sandbox midsecret
    // protected $midcode = '41'; //sandbox mid
    //FTD Traffic
    //protected $midcode = "129";
    //protected $midsecret =  "73d586df-f2d1-4a38-a2b5-dc6cad61a906";
    //Verified
    //protected $midcode = "130";
    //protected $midsecret =  "3bfa2849-116b-46a6-b28e-525ae4c219cd";

    //API create CHECKOUT for STRADAPAY
    public function getStradaPayCheckoutDetail(Request $request, $accId)
    {
        $date = Carbon::now()->subDays(3);
        CheckoutDetail::where('payment_partner','p4')->where('created_at', '<', $date)->delete();

        $checkaccId = PFourPaymentMethod::where('accountId',$accId)->where('status','1')->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $amount = round($request->amount,2);
        // $email = $request->email;
        $currency = strtoupper($request->currency);
        // $firstName = $request->first_name;
        // $lastname = $request->last_name;
        // $nationality = $request->nationality;
        // $countryOfResidence = $request->country_of_residence;
        // || empty($email) || empty($firstName) || empty($lastname)|| empty($nationality) || empty($countryOfResidence)
        if (empty($amount) || empty($currency) ) {
           return response()->json(['error' => 'Incomplete Parameters'], 400);
        }

        do {
            $uuid = Str::uuid()->toString(); // Generate UUID
        } while (CheckoutDetail::where('checkout_id', $uuid)->exists()); // Check if UUID exists

        //  $id = '232ccc-ccc-kvkbkjvjksfjkbjkbfjksjkfskjfkbw';

        //  // Find the position of 'ccc-ccc-'
        //  $position = strpos($id, 'ccc-ccc-');

        //  if ($position !== false) {
        //      // Extract the part before 'ccc-ccc-'
        //      $result = substr($id, 0, $position);
        //      echo $result; // Output: 232
        //  } else {
        //      echo "Substring '-ccc-ccc-' not found.";
        //  }

        $checkoutDetailObj = new CheckoutDetail();

        $checkoutDetailObj->accId = $checkaccId->accountId;
        $checkoutDetailObj->payment_partner = 'p4';
        $checkoutDetailObj->amount =  round($amount,2);
        $checkoutDetailObj->currency = $currency;
        $checkoutDetailObj->amount_from = $amount;
        $checkoutDetailObj->currency_from = $currency;
        // $checkoutDetailObj->email = $email;
        $checkoutDetailObj->checkout_id = $uuid;
        $checkoutDetailObj->status = '0';

        $checkoutDetailObj->save();

        $checkout_id = $checkoutDetailObj->checkout_id;


        return response()->json([
            'amount' => number_format($amount,2),
            'currency'=> $currency,
            'checkout_id' => $checkout_id ,
            'link' =>  'https://payment.ryzen-pay.com/payment/p4/payment-page/'.$checkout_id,
        ],200);

    }

    // Helper method to generate the hash
    protected function generateHash($data,$payby)
    {
        if($payby == "card"){
            $plaintext = $data['mid_code'] . "~" . $data['card_holder_name'] . "~" . $data['card_number'] . "~" . $data['card_expiry_year'] . "~" . $data['card_expiry_month'] . "~" . number_format($data['amount'],2). "~" . $data['currency'] . "~" . $data['mid_secret'];
        }elseif($payby == "other"){
            $plaintext = $data['mid_code'] . "~" . $data['merchant_ref_no'] . "~" . number_format($data['amount'],2). "~" . $data['currency'] . "~" . $data['mid_secret'];
        }else{
            return "false";
        }
        return hash('sha256', $plaintext);
    }

    // The callback route to handle postback API data
    public function handleCallback(Request $request)
    {
        // Read raw POST body and parse manually (because x-www-form-urlencoded might not be parsed)
        $rawBody = file_get_contents('php://input');
        parse_str($rawBody, $data);

        Log::info('P4 Callback Raw Body:', ['body' => $rawBody]);
        Log::info('Parsed Data:', $data);
        Log::info('Received P4 callback request all data:', $request->all());

        $midcode   = $data['sid'] ?? null;          // Mid code
        $txid      = $data['txid'] ?? null;         // Transaction ID
        $amount    = isset($data['amount']) ? number_format((float) $data['amount'], 2, '.', '') : null; // Ensure 2 decimal points
        $status    = $data['status'] ?? null;       // Status (APPROVED/DECLINED/PENDING)
        $currency  = $data['currency'] ?? null;     // Currency
        $merchantRefNo = $data['Ref1'] ?? null;     // Merchant reference ID
        $message   = $data['message'] ?? null;      // Response message
        $receivedHash = $data['hash'] ?? null;      // Received hash

        Log::info('Callback Parameters Received:', [
            'sid'       => $midcode,
            'txid'      => $txid,
            'amount'    => $currency.' '.$amount,
            'status'    => $status,
            'merchantRefNo'=> $merchantRefNo,
            'hash'      => $receivedHash,
        ]);

        $midSecret = PFourPaymentMethod::where('mid_code',$midcode)->first();
        $calculatedHash = hash('sha256', $midcode . $txid . $amount . $midSecret->mid_secret);
        $calculatedHash2= hash('sha256', $midcode . "~" . $txid . "~" . $merchantRefNo . "~" . $status  . "~" . $amount . "~" . $midSecret->mid_secret);

        $transaction = Transaction::where('payment_id', $txid)->first();

        if (!$transaction) {
            Log::error("Transaction not found: " . $txid);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->payment_status = ucfirst(strtolower($status));
        $transaction->description = ucfirst($message);
        $transaction->save();

        Log::info("Transaction updated successfully: " . $txid);
        if ($receivedHash !== $calculatedHash && $receivedHash !== $calculatedHash2) {
            Log::error("Hash mismatch! Possible fraud attempt.", [
                'expected_hash_1' => $calculatedHash,
                'expected_hash_2' => $calculatedHash2,
                'received_hash' => $receivedHash,
            ]);
            return response()->json(['error' => 'Invalid hash'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Transaction updated successfully'], 200);

    }

    public function processStradaPay(Request $request,$checkout_id)
    {
        $check = CheckoutDetail::where('checkout_id',$checkout_id)->where('status','0')->first();
        if($check == null){
            return redirect('/error-payment-page');
            // return response()->json(['error' => 'Checkout Timed Out'],401);
        }
        $check->status = '1';
        $check->save();

        $expiryDate = $request->input('expiry_date');
        list($month, $year) = explode('/', $expiryDate);

        $month = trim($month);
        $year = trim($year);

        $rules = [
            // 'midcode' => 'required|string',
            // 'amount' => 'required|numeric',
            // 'currency' => 'required|string',
            'country' => 'required|string',
            // 'notification_url' => 'required|string',
            // 'return_url' => 'required|string',
            'merchant_ref_no' => 'required|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'card_holder_name' => 'required|string',
            'card_number' => 'required|string',
            // 'card_expiry_month' => 'required|string',
            // 'card_expiry_year' => 'required|string',
            'cvv' => 'required|string',
            // 'risk_data.user_category' => 'string',
            // 'risk_data.device_fingerprint' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {

            return response()->json([
                'error' => 'Validationz failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['card_expiry_month'] = $month;
        $data['card_expiry_year'] = $year;
        $data['amount'] = $check->amount;
        $data['currency'] = $check->currency;
        $data['merchant_ref_no'] = $checkout_id;
        $data['mid_code'] = $check->mid_code;
        $data['mid_secret'] = $check->mid_secret;
        $payby = $check->payby;

        $hash = $this->generateHash($data,$payby);

        $payload = [
            'request_type' => 'deposit',
            'data' => [
                'midcode' => $data['mid_code'],
                'payby' => $payby,
                'amount' => number_format($data['amount'], 2, '.', ''),
                'hash' => $hash,
                'currency' => $data['currency'],
                'country' => $data['country'],
                'notification_url' => 'https://payment.ryzen-pay.com/api/payment/p4/notification',
                'return_url' => 'https://payment.ryzen-pay.com/payment/p4/returnurl-response',
                'merchant_ref_no' => $checkout_id,
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'city' => $data['city'],
                'address' => $data['address'],
                'state' => $data['state'],
                'zipcode' => $data['zipcode'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'checkout_type' => 'seamless',
                "ipaddress"=> $request->ip() ,
                'card_holder_name' => $data['card_holder_name'],
                'card_number' => $data['card_number'],
                'card_expiry_month' => $month , //$data['card_expiry_month'],
                'card_expiry_year' => $year , //$data['card_expiry_year'],
                'cvv' => $data['cvv'],
                'risk_data' => [
                    'user_category' =>  'default',
                    'device_fingerprint' => 'test',
                ],
                'custom_field_1' => 'checkout_id: '.$checkout_id,
                'custom_field_2' => $_SERVER['REMOTE_ADDR'],
                'custom_field_3' => $check->accId,
                'custom_field_4' => 'string44',
                'custom_field_5' => 'string54',
            ]
        ];

        try {
            $client = new Client();
            $response = $client->post($this->apiUrl, [
                'json' => $payload,
                'headers' => [
                    'X-Version' => '1.0',
                    'Content-Type' => 'application/json',
                ]
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('P4 response body after api call for '.$checkout_id .' : ',$responseBody);

            if ($responseBody['success'] === true)
            {
                Log::info('Success key exists and value is true');
                $transaction = new Transaction();
                $transaction->account_id =  $check->accId;
                $transaction->currency= $responseBody['data']['currency']; // currency in which we recieved in
                $transaction->amount= $responseBody['data']['amount'];  // amount in which we recieved in
                $transaction->from_currency= $responseBody['data']['currency'];
                $transaction->from_amount= $responseBody['data']['amount'];
                $transaction->checkout_id= $checkout_id;
                $transaction->payment_id= $responseBody['data']['transaction_id'];
                $transaction->payment_status= ucfirst(strtolower($responseBody['data']['status']));
                $transaction->created_at = Carbon::parse($responseBody['data']['transaction_date']);

                if ($check->accId == 'p4/PaymentLink')
                {
                    $transaction->description = 'P4 Payment Link : '. ucfirst($responseBody['data']['message']) ?? 'no message found';
                    $transaction->status= 'p4/PL';
                }else{
                    $company_name = PFourPaymentMethod::where('accountId', $check->accId)->first();
                    $transaction->description = ucfirst($responseBody['data']['message']) ?? 'No message found';
                    $transaction->status= 'p4';
                }

                $transaction->save();

                if (isset($responseBody['data']['checkout_url'])) {
                    return redirect()->to($responseBody['data']['checkout_url']);
                }

                return redirect('/p4/thank-you-page/'.$checkout_id);
              } else {
                Log::info('(Pay-by-Link) P4 transaction failed for '.$checkout_id, $responseBody);

                $transaction = new Transaction();
                $transaction->account_id = $check->accId;
                $transaction->currency = $responseBody['data']['currency'] ?? $data['currency'];
                $transaction->amount = $responseBody['data']['amount'] ?? $data['amount'];
                $transaction->from_currency = $responseBody['data']['currency'] ?? $data['currency'];
                $transaction->from_amount = $responseBody['data']['amount'] ?? $data['amount'];
                $transaction->checkout_id = $checkout_id;
                $transaction->payment_id = $responseBody['data']['transaction_id'] ?? '-';
                $transaction->payment_status = 'Declined';

                // Error message from error[] array
                $errorMsg = isset($responseBody['error'][0]['errormessage']) ? $responseBody['error'][0]['errormessage'] : 'No error message';

                if ($check->accId == 'p4/PaymentLink') {
                    $transaction->description = 'Payment Link Error: ' . $errorMsg;
                    $transaction->status = 'p4/PL';
                } else {
                    $transaction->description = 'Error: ' . $errorMsg;
                    $transaction->status = 'p4';
                }

                $transaction->save();

                return redirect('/p4/thank-you-page/'.$checkout_id)->with('error', 'Transaction failed: ' . $errorMsg);
              }
        } catch (\Exception $e) {
            Log::error("Error during API request: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getStradaPayHostToHost(Request $request, $accId)
    {
        try {
            // Cleanup old checkouts
            $date = Carbon::now()->subDays(3);
            CheckoutDetail::where('payment_partner', 'p4')->where('created_at', '<', $date)->delete();

            // Check account validity
            $checkaccId = PFourPaymentMethod::where('accountId', $accId)->where('status', '1')->first();
            if (!$checkaccId) {
                return response()->json(['error' => 'Unauthorized Account Id'], 401);
            }

            // Validation
            $rules = [
                'amount' => 'required|numeric|min:0.01',
                'currency' => 'required|string',
                'country' => 'required|string',
                'merchant_ref_no' => 'required|string',
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'city' => 'required|string',
                'address' => 'required|string',
                'state' => 'required|string',
                'zipcode' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|email',
                'card_holder_name' => 'required|string',
                'card_number' => 'required|string|digits_between:12,19',
                'expiry_date' => 'required',
                'cvv' => 'required|string|digits_between:3,4',
            ];
            Log::info("after rules");
            $validator = Validator::make($request->all(), $rules);
            Log::info("after defining validator");

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors(),
                ], 422);
            }
            Log::info("after validator fails if block");

            $data = $validator->validated();
            Log::info("var data : ", $data);
            $expiryDate = $data['expiry_date'] ?? null;
            if (!$expiryDate || !str_contains($expiryDate, '/')) {
                Log::error("Invalid or missing expiry_date: " . json_encode($expiryDate));
                return response()->json(['error' => 'Invalid expiry_date format. Expecting MM/YYYY'], 422);
            }

            [$month, $year] = explode('/', $expiryDate);
            // [$month, $year] = explode('/', $data['expiry_date']);
            Log::info("after explode");

            do {
                $uuid = Str::uuid()->toString();
            } while (CheckoutDetail::where('checkout_id', $uuid)->exists());

            $checkout = CheckoutDetail::create([
                'accId' => $checkaccId->accountId,
                'payment_partner' => 'p4',
                'amount' => round($data['amount'], 2),
                'currency' => strtoupper($data['currency']),
                'amount_from' => round($data['amount'], 2),
                'currency_from' => strtoupper($data['currency']),
                'checkout_id' => $uuid,
                'status' => '1',
            ]);

            // Enrich payload
            $data['card_expiry_month'] = trim($month);
            $data['card_expiry_year'] = trim($year);
            $data['amount'] = $checkout->amount;
            $data['currency'] = $checkout->currency;
            $data['merchant_ref_no'] = $checkout->checkout_id;
            $data['mid_code'] = $checkout->mid_code;
            $data['mid_secret'] = $checkout->mid_secret;
            $payby = $checkout->payby;

            $hash = $this->generateHash($data,$payby);

            $payload = [
                'request_type' => 'deposit',
                'data' => [
                    'midcode' => $data['mid_code'],
                    'payby' => $payby,
                    'amount' => number_format($data['amount'], 2, '.', ''),
                    'hash' => $hash,
                    'currency' => $data['currency'],
                    'country' => $data['country'],
                    'notification_url' => 'https://payment.ryzen-pay.com/api/payment/p4/notification',
                    'return_url' => 'https://payment.ryzen-pay.com/payment/p4/thank-you-page/' . $uuid,
                    'merchant_ref_no' => $checkout->checkout_id,
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'city' => $data['city'],
                    'address' => $data['address'],
                    'state' => $data['state'],
                    'zipcode' => $data['zipcode'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'checkout_type' => 'seamless',
                    'ipaddress' => $request->ip(),
                    'card_holder_name' => $data['card_holder_name'],
                    'card_number' => $data['card_number'],
                    'card_expiry_month' => $data['card_expiry_month'],
                    'card_expiry_year' => $data['card_expiry_year'],
                    'cvv' => $data['cvv'],
                    'risk_data' => [
                        'user_category' => 'default',
                        'device_fingerprint' => 'test'
                    ],
                    'custom_field_1' => 'checkout_id: ' . $checkout->checkout_id,
                    'custom_field_2' => $_SERVER['REMOTE_ADDR'],
                    'custom_field_3' => $checkout->accId,
                    'custom_field_4' => $data['firstname'] . ' ' . $data['lastname'],
                    'custom_field_5' => $data['email']
                ]
            ];

            $client = new Client();
            $response = $client->post($this->apiUrl, [
                'json' => $payload,
                'headers' => [
                    'X-Version' => '1.0',
                    'Content-Type' => 'application/json',
                ]
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info("(StradaPay H2H) P4 response for {$uuid}:", $responseBody);

            $paymentData = $responseBody['data'];

            $errorMsg = isset($responseBody['error'][0]['errormessage']) ? $responseBody['error'][0]['errormessage'] : 'No message';

            Transaction::create([
                'account_id' => $checkout->accId,
                'currency' => $paymentData['currency'] ?? $data['currency'],
                'amount' => $paymentData['amount'] ?? $data['amount'],
                'from_currency' => $paymentData['currency'] ?? $data['currency'],
                'from_amount' => $paymentData['amount'] ?? $data['amount'],
                'checkout_id' => $checkout->checkout_id,
                'payment_id' => $paymentData['transaction_id'] ?? '-',
                'payment_status' => ucfirst(strtolower($paymentData['status'] ?? "Declined")),
                'description' => ($checkout->accId === 'p4/PL')
                    ? 'P4 Payment Link: ' . ($paymentData['message'] ??  $errorMsg)
                    : ($paymentData['message'] ?? $errorMsg),
                'status' => ($checkout->accId === 'p4/PaymentLink') ? 'p4/PL' : 'p4',
                'created_at' => Carbon::parse($paymentData['transaction_date']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment initiated successfully',
                'redirect_url' => $paymentData['checkout_url'] ?? null,
                'checkout_id' => $checkout->checkout_id,
            ]);

            // Log::warning("Payment gateway failed for {$uuid}", $responseBody);
            // return response()->json(['error' => 'Payment failed', 'response' => $responseBody], 400);

        } catch (\Exception $e) {
            Log::error("Payment error for checkout id : $checkout->checkout_id .....  " . $e->getMessage());

            Transaction::create([
                'account_id'     => $checkout->accId,
                'currency'       => $data['currency'] ?? null,
                'amount'         => $data['amount'] ?? 0,
                'from_currency'  => $data['currency'] ?? null,
                'from_amount'    => $data['amount'] ?? 0,
                'checkout_id'    => $checkout->checkout_id,
                'payment_id'     => $responseBody['data']['transaction_id'] ?? null,
                'payment_status' => 'Failed',
                'description'    => $responseBody['error'][0]['errormessage'] ?? 'Payment failed',
                'status'         => 'p4',
                'created_at'     => now(),
            ]);

            return response()->json([
                'error'    => 'Payment failed',
                'checkout_id' => $checkout->checkout_id,
                'response' => $responseBody
            ], 400);
        }
    }

    //return URL REsponse
    public function handleReturnUrl(Request $request)
    {
        Log::info('P4 Return URL Data Received:', $request->query()); // Log for debugging

        $midcode  = $request->query('sid');        // Mid code
        $txid     = $request->query('txid');       // Transaction ID
        $amount   = number_format($request->query('amount'), 2, '.', ''); // Ensure 2 decimal places
        $status   = $request->query('status');     // Status (APPROVED / DECLINED / PENDING)
        $currency = $request->query('currency');   // Currency
        $refno    = $request->query('refno');      // Transaction reference number
        $receivedHash = $request->query('hash');   // Received hash

        $midSecret = PFourPaymentMethod::where('mid_code',$midcode)->first();
        $calculatedHash = hash('sha256', $midcode . $txid . $amount . $midSecret->mid_secret);

        if ($receivedHash !== $calculatedHash) {
            Log::error("Return URL Hash mismatch! Possible fraud attempt.");
            return redirect('/p4/thank-you-page')->with('error', 'Transaction verification failed.');
        }

        $transaction = Transaction::where('payment_id', $txid)->first();

        if (!$transaction) {
            Log::error("Transaction not found for return URL: " . $txid);
            return redirect('/p4/thank-you-page')->with('error', 'Transaction not found.');
        }

        $transaction->payment_status = ucfirst(strtolower($status));
        $transaction->save();

        Log::info("Transaction updated successfully via return URL: " . $txid);

        try{
            $detail = PFourPaymentMethod::where('accountId',$transaction->account_id)->first();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => $detail->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p4/'.$refno, [
                'headers' => $headers,
            ]);
            $statusCode = $resp->getStatusCode();
            Log::info("received P4 status-code response. Status code: {$statusCode}");

        }
        catch(RequestException $e){
            Log::warning("response p4 exception catch : ".$e->getMessage());
        }
        finally{
            return redirect('/p4/thank-you-page/'.$refno);
        }
    }

    public function showThankYouPage($checkout_id)
    {
        $transaction = Transaction::where('checkout_id',$checkout_id)->first();

        if($transaction == null)
        {
            session()->flash('error', 'Transaction Process Incomplete or Not Found');
            return view('payment.stradapay.thank-you-page');
        }

        session()->flash('success', 'Transaction Process Complete!');
        $amount = $transaction->amount;
        $currency= $transaction->currency;
        $status= $transaction->payment_status;
        $description = $transaction->description;

        return view('payment.stradapay.thank-you-page', compact('amount','currency','status','description'));
    }

    //get StradaPay Payment Status
    public function stradapayGetPaymentStatus($accId, $checkout_id)
    {
        $checkaccId = PFourPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $checkCheckoutId = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p4')->first();
        if($checkCheckoutId == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        return response()->json([
            'data' => [
                // "account_id" => $checkCheckoutId->account_id,
                "currency" => $checkCheckoutId->currency,
                "amount" => number_format($checkCheckoutId->amount,2),
                "checkout_id" => $checkCheckoutId->checkout_id,
                "payment_id" => $checkCheckoutId->payment_id,
                "payment_status" => ucfirst($checkCheckoutId->payment_status),
                "description" => $checkCheckoutId->description,
                "created_at" => $checkCheckoutId->created_at,
            ]
        ],200);
    }
}
