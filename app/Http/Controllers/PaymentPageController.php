<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\Company;
use App\Models\PFourPaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwoPaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpseclib\Crypt\RSA;
use GuzzleHttp\Exception\RequestException;

class PaymentPageController extends Controller
{
    protected $paybisPrivateKeyz ='-----BEGIN PRIVATE KEY-----
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
EhrgdNNiDKgtsUOcOBeZP22OdWe0dg==
-----END PRIVATE KEY-----
';

     // The public key of paybis for signature verification
     private $publicKey = '-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEApR7LLr506sbJjzs5BuBf
Ubu5Efi+fFN3XlGx6wrKimZ5OSSImHOA8T5fRy/teRriD/92+V18pAh6jmMR7r9E
nnIskJ45IwlMbrp6HRQ5GGt2phHxwj31MvkB+JahqDZrJ6GCwWSd/i7gZjizLy03
pxzV1Sw02342pQMtHX8QgwV5j3/J8Btez5bANHZn5Zp9FS9N6pkedOiZWjiSWOFQ
YUk73VhyW5TjXN5MYQ6FlHmPdwm/Qe/x4DZYXLNAMlFL8Tsb3xNkekJiJPKyr0h2
vqmbEdc9WYtaJAilVS6Yt0QOJtymmQsowCbP7mUFW/i7q8ayjrRUyLnzmoR8H+yY
G+B8lcpu7Aqt0lxUTMRm5KwnTkUyZrimwReWE8LVc68Ae7t4Qxj1dN6nLegDWO7G
BynD7D8ESJ6bNp6GCbc5ntY1T5g+HIGrff7DclcYfzu6RNVgKFlnLxue9J6iJv8q
4wFtn3OM3hxDG/SDk+YUlXiVeUNjPjoA8Z4aEE7OkJBouykLVSiHn4nVrN0WZ1+y
ouYyGwFbL2Vw5G4QR+bi3CZP6rYk9X3A8/xzXjDSYoAqK1+0/7Qncmapbr1Id8qc
huUr+tJq91Ua+EjpdjfaxOrSVBts0iYujY0ahrVCFYBlqu89MSOW4tM4BEgkOeN/
IrZj8Jj85onbaoJr1svCpZUCAwEAAQ==
-----END PUBLIC KEY-----
';

    /////////////////////   API for Paystrax CHeckout Details  //////////////////////
    public function paystraxCheckoutDetail($accId ,Request $request)
    {
         // Get the current time minus 20 minutes
        $date = Carbon::now()->subMinutes(60);

        // Delete records where 'created_at' is older than 20 minutes
        CheckoutDetail::where('payment_partner','p1')->where('created_at', '<', $date)->delete();

        // $checkaccId = Company::where('accountId',$accId)->where('payment_partner','Paystrax')->first();
        $checkaccId = POnePaymentMethod::where('accountId',$accId)->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $amount = $request->amount;
        $currency = strtoupper($request->currency);

        $url = "https://eu-prod.oppwa.com/v1/checkouts";

        if($currency == 'USD'){
            $entityIdz = '8ac9a4cb9242d2fb019247ef4e6000a3'; //for USD
        }
         elseif($currency == 'GBP'){
            $entityIdz = '8ac9a4cb9242d2fb019247f049ea00be'; // for GBP
        }
         elseif($currency == 'EUR'){
            $entityIdz = '8ac9a4cb9242d2fb019247efdf5400b0'; //for EUR
        }
         else {
            return  response()->json(['error' => 'This currency is not valid'],401);
        }

        $data = "entityId=".$entityIdz .
                    "&amount=".$amount .
                    "&currency=".$currency.      //$currency_code
                    "&paymentType=DB" .
                    "&integrity=true";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer OGFjZGE0Y2I5MTRmYzY1YzAxOTE1YTM0MGU0YjJiYmV8MnlOeXdEckRwbUptbnRIMw=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $chkId = json_decode($responseData);

        $checkout_id = $chkId->id;
        $checkout_integrity = $chkId->integrity;

        $checkoutDetailObj = new CheckoutDetail();

        $checkoutDetailObj->accId = $accId;
        $checkoutDetailObj->payment_partner = 'p1';
        $checkoutDetailObj->amount = $amount;
        $checkoutDetailObj->currency = $currency;
        $checkoutDetailObj->checkout_id = $checkout_id;
        $checkoutDetailObj->checkout_integrity = $checkout_integrity;
        $checkoutDetailObj->status = '0';

        $checkoutDetailObj->save();

        return response()->json([
            'amount' => $amount,
            'currency'=> $currency,
            'accountId' => $checkoutDetailObj->accId,
            'checkout_id' => $checkoutDetailObj->checkout_id,
            'link' => 'https://payment.ryzen-pay.com/payment/p1/payment-page/'.$checkoutDetailObj->checkout_id
        ],200);
    }

    public function paystraxGetPaymentStatus($accId,$checkout_id)
    {
        $checkaccId = POnePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }
        $checkCheckoutId = Transaction::where('checkout_id',$checkout_id)->where('status','p1')->first();
        if($checkCheckoutId == null){
            return response()->json(['error' => 'Unauthorized Checkout Id'],401);
        }

        return response()->json([
            'data' => [
                "account_id" => $checkCheckoutId->account_id,
                "currency" => $checkCheckoutId->currency,
                "amount" => $checkCheckoutId->amount,
                "checkout_id" => $checkCheckoutId->checkout_id,
                "payment_id" => $checkCheckoutId->payment_id,
                "payment_status" => ucfirst($checkCheckoutId->payment_status),
                "description" => $checkCheckoutId->description,
                "created_at" => $checkCheckoutId->created_at,
            ]
        ],200);
    }

    //////////////////// WEB ROUTES ///////////////////////////////

    public function showPaystraxPaymentPage($checkout_id)
    {
         // Get the current time minus 15 minutes
         $date = Carbon::now()->subMinutes(20);

         // Delete records where 'created_at' is older than 20 minutes
         CheckoutDetail::where('payment_partner','p1')->where('created_at', '<', $date)->delete();

        $check= CheckoutDetail::where('checkout_id',$checkout_id)->first();
        if($check == null){
            return redirect('/error-payment-page');
            // return response()->json(['error' => 'Checkout Timed Out'],401);
        }
        $accId = $check->accId;

        $checkaccId = POnePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized'],401);
        }

        $data = CheckoutDetail::where('checkout_id',$checkout_id)->where('accId',$accId)->where('status','0')->first();
        if($data == null){
            return response()->json(['error' => 'Checkout Expired'],401);
        }
        $companyName = $checkaccId->company->company_name;
        $amount = $data->amount;
        $currency = $data->currency;
        $checkout_id = $data->checkout_id;
        $integrity = $data->checkout_integrity;
        $created_at = $data->created_at;

        return view('payment.paystrax.paystrax-payment-page',compact('currency','amount','integrity','checkout_id','companyName','created_at'));
    }

    //  to handle PayStrax payment result and status check
    public function paystraxPaymentResult($checkout_id, Request $request)
    {
        Log::info("paystrax request data : ");
        Log::info($request->all());
        $checkoutDetails = CheckoutDetail::where('checkout_id',$checkout_id)->first();

        if ($checkoutDetails == null) {
            return  response()->json([
                'error' => 'Unauthorized Access'
            ],401);
        }

        $currency = $checkoutDetails->currency;

        $resourcePath = $request->get('resourcePath');
        $baseUrl = "https://eu-prod.oppwa.com/";

        $url = $baseUrl . $resourcePath;

        if($currency == 'USD'){
            $entityId = '8ac9a4cb9242d2fb019247ef4e6000a3'; //for USD
        } else if($currency == 'GBP'){
            $entityId = '8ac9a4cb9242d2fb019247f049ea00be'; // for GBP
        } else if($currency == 'EUR'){
            $entityId = '8ac9a4cb9242d2fb019247efdf5400b0'; //for EUR
        } else {
            Log::warning("paystrax request currency invalid in webhook hanndling");
            return  response()->json([
                'error' => 'This currency is not valid'
            ],401);
        }

        $paymentStatus = $this->getPaystraxPaymentStatus($url,$entityId);
        Log::info('Payment Status: ' . json_encode($paymentStatus, JSON_PRETTY_PRINT));

        if(isset($paymentStatus['result']) && $paymentStatus['result']['description'] == 'Transaction succeeded')
        {
            $transaction = new Transaction();

            $transaction->currency = strtoupper($paymentStatus['currency']);
            $transaction->amount = number_format($paymentStatus['amount'], 2, '.', '');
            $transaction->from_currency = strtoupper($paymentStatus['currency']);
            $transaction->from_amount = number_format($paymentStatus['amount'], 2, '.', '');
            $transaction->checkout_id = $checkoutDetails->checkout_id;
            $transaction->payment_id = $paymentStatus['id'];
            $transaction->payment_status = 'Succeeded';

            if($checkoutDetails->accId == 'p1/PaymentLink'){
                $transaction->account_id = $checkoutDetails->accId;
                $transaction->description = 'Payment via P1 Payment Link' ;
                $transaction->status = 'p1/PL';
            }else{
                $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();
                $transaction->account_id = $companyDetails->accountId;
                $transaction->description = 'Payment for '. $companyDetails->company_name;
                $transaction->status = 'p1';
            }

            $transaction->save();

            $checkoutDetails->status = '1';
            $checkoutDetails->save();

            $p1details = POnePaymentMethod::where('accountId',$companyDetails->accountId)->first();

            if($checkoutDetails->accId == 'p1/PaymentLink'){
                return redirect('/p1/create-payment-link')->with('success','Payment Success!');
            }else{
                return redirect()->to($p1details->redirect_url .'/RyzenPay/p1/checkout-id/'.$transaction->checkout_id);
            }
        }
        else
        {
            $checkoutDetails->status = '1';
            $checkoutDetails->save();
            return view('result-page')->with('error', 'Transaction Failed! Reason: '.$paymentStatus['result']['description']);
            // return response()->json([
            //     'error'=> 'Transaction Failed! Reason: '.$paymentStatus['result']['description']
            // ]);
            // print_r($paymentStatus, true);
        }

    }

    //GET payment status endpoint
    private function getPaystraxPaymentStatus($url, $entityId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?entityId=" . $entityId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer OGFjZGE0Y2I5MTRmYzY1YzAxOTE1YTM0MGU0YjJiYmV8MnlOeXdEckRwbUptbnRIMw==' // bearer token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //true in production
        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        return json_decode($responseData, true);
    }

    public function testcustomer()
    {
        return view('cust-checkout-page');
    }


    //////// PAYBIS PAYMENT /////////
    //// API ROUTES ////
    public function getCryptoPaymentRId($accId ,Request $request)
    {
         // Get the current time minus 7dayss
         $date = Carbon::now()->subDays(7);

         // Delete records where 'created_at' is older than 20 minutes
         CheckoutDetail::where('payment_partner','p2')->where('created_at', '<', $date)->delete();

         // $checkaccId = Company::where('accountId',$accId)->where('payment_partner','Paystrax')->first();
         $checkaccId = PTwoPaymentMethod::where('accountId',$accId)->first();

         if($checkaccId == null){
             return response()->json(['error' => 'Unauthorized Account Id'],401);
         }

         $amount = $request->amount;
         $currency = strtoupper($request->currency);

         $paybisPrivateKey = $this->paybisPrivateKeyz;

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


        try{
            $client = new Client();

            // $requestBody = '{"isReceivedAmount":false,"currencyCodeFrom":"'.$request->from_currency .'","currencyCodeTo":"'.$request->to_currency .'","amount":"'.$amount .'","directionChange":"from","paymentMethod": "ryzen-ventures-credit-card"}';
            $requestBody = '{"isReceivedAmount":false,"currencyCodeFrom":"'.$currency .'","currencyCodeTo":"USDT-TRC20-SHASTA","amount":"'.$amount .'","directionChange":"from","paymentMethod": "ryzen-ventures-credit-card"}';
            // dd($requestBody);
            $requestBodyHash = hash('sha512', $requestBody);
            $verifier = new RSA();
            $verifier->setSignatureMode(RSA::SIGNATURE_PSS);  //SIGNATURE_PSS //SIGNATURE_PKCS1
            $verifier->setHash('sha512');
            $verifier->setMGFHash('sha512');
            $verifier->setSaltLength(64);
            $verifier->loadKey($paybisPrivateKey);
            $signature = base64_encode($verifier->sign($requestBodyHash));

            $response = $client->request('POST', 'https://widget-api.sandbox.paybis.com/v2/quote', [
                'body' => $requestBody,
                'headers' => [
                  'Authorization' => 'Bearer c8e1bb5ced6cfa52144501ad9911da00',
                  'accept' => 'application/json',
                  'content-type' => 'application/json',
                  'x-request-signature' => $signature,
                ],
              ]);

            if ($response->getStatusCode() === 200)
            {
              $responseData = json_decode($response->getBody());
                  if(isset($responseData->paymentMethodErrors)){
                      return redirect()->back()->with('error','Entered Amount is Insufficient!');
                  }
              $quoteId = $responseData->id;
              $requestedAmount = $responseData->requestedAmount->currencyCode.' '.$responseData->requestedAmount->amount;
              $paymentMethodsAvailable =  $responseData->paymentMethods;
              $amount = $paymentMethodsAvailable[0]->amountTo->amount;
              $currency = $paymentMethodsAvailable[0]->amountTo->currencyCode;

               $checkoutDetailObj = new CheckoutDetail();

               $checkoutDetailObj->accId = $accId;
               $checkoutDetailObj->payment_partner = 'p2';
               $checkoutDetailObj->amount =  $amount;
               $checkoutDetailObj->currency = $currency;
               $checkoutDetailObj->amount_from = $request->amount;
               $checkoutDetailObj->currency_from = strtoupper($request->currency);;
               $checkoutDetailObj->checkout_id = null;
               $checkoutDetailObj->status = '0';

            } else {
                return redirect()->back()->with('error','Unexpected response status: ');
            }

            try{
                $client2 = new Client();

                $requestBody2 = '{"locale":"en","passwordless":false,"flow":"buyCrypto","partnerUserId":"'.$checkaccId->company->id.'-ccc-ccc-'.$request->cust_id.'","quoteId":"'.$quoteId.'","paymentMethod":"ryzen-ventures-credit-card","email":"'.$request->cust_email.'","cryptoWalletAddress":{"address":"TCwMwfYNdz8xMR6YF74RynQREB4nXG9iYa","currencyCode":"USDT-TRC20-SHASTA"}}';
                $requestBodyHash2 = hash('sha512', $requestBody2);
                $verifier2 = new RSA();
                $verifier2->setSignatureMode(RSA::SIGNATURE_PSS);  //SIGNATURE_PSS
                $verifier2->setHash('sha512');
                $verifier2->setMGFHash('sha512');
                $verifier2->setSaltLength(64);
                $verifier2->loadKey($paybisPrivateKey);
                $signature2 = base64_encode($verifier2->sign($requestBodyHash2));

                $response2 = $client2->request('POST', 'https://widget-api.sandbox.paybis.com/v2/request', [
                    'body' => $requestBody2 ,
                    'headers' => [
                      'Authorization' => 'Bearer c8e1bb5ced6cfa52144501ad9911da00',
                      'accept' => 'application/json',
                      'content-type' => 'application/json',
                      'x-request-signature' => $signature2,
                    ],
                  ]);

                if ($response2->getStatusCode() === 200)
                {
                  $res2 = json_decode($response2->getBody());
                //   dd($res);
                  $requestId = $res2->requestId;   // $oneTimeToken = $res->oneTimeToken;

                  $checkoutDetailObj->checkout_id = $requestId;
                  $checkoutDetailObj->save();

                  return response()->json([
                    'amount' => $amount,
                    'currency'=> $currency,
                    'accountId' => $checkoutDetailObj->accId,
                    'checkoutId' => $requestId,
                    'link' => 'https://payment.ryzen-pay.com/payment/p2/payment-page/'.$requestId
                  ]);
                }else{
                  return redirect()->back()->with('error','Unexpected response status: ' . $response->getStatusCode());
                }
            } catch (\Exception $e) {
                // Handle API call exceptions
                $body  = $e->getResponse()->getBody()->getContents();
                $decoded = json_decode($body,true);
                return response()->json([
                    'message' => 'Unique constraint violation for cust_id and cust_email. cust_id can be associated only with one cust_email (1:1). Each cust_email can be associated with many cust_ids (1:M)',
                ]);
                // dd( $decoded['message'] );
                // return redirect()->back()->with('error','Error: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Handle API call exceptions
            $errorMessage = 'An Unknown Error Occured';
            $response = json_decode($e->getResponse()->getBody()->getContents(), true);
            if (isset($response['message'])) {
                $errorMessage = $response['message'];
            }
            return redirect()->back()->with('error','Error: ' .$errorMessage);
        }
    }

    public function paybisGetPaymentStatus($accId, $checkout_id)
    {
        $checkaccId = PTwoPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }
        $checkCheckoutId = Transaction::where('checkout_id',$checkout_id)->where('status','p2')->first();
        if($checkCheckoutId == null){
            return response()->json(['error' => 'Unauthorized Checkout Id or Transaction process not completed'],401);
        }

        return response()->json([
            'data' => [
                "account_id" => $checkCheckoutId->account_id,
                "currency" => $checkCheckoutId->currency,
                "amount" => $checkCheckoutId->amount,
                "from_currency" => $checkCheckoutId->from_currency,
                "from_amount" => $checkCheckoutId->from_amount,
                "checkout_id" => $checkCheckoutId->checkout_id,
                "payment_id" => $checkCheckoutId->payment_id,
                "payment_status" => ucfirst($checkCheckoutId->payment_status),
                "description" => $checkCheckoutId->description,
                "created_at" => $checkCheckoutId->created_at,
            ]
        ],200);
    }

    //// WEB ROUTES ////
    public function showPaybisPaymentPage($checkout_id)
    {
         // Get the current time minus 7days
         $date = Carbon::now()->subDays(7);

         // Delete records where 'created_at' is older than 7days
         CheckoutDetail::where('payment_partner','p2')->where('created_at', '<', $date)->delete();

        $check= CheckoutDetail::where('checkout_id',$checkout_id)->first();
        if($check == null){
            return response()->json(['error' => 'Checkout Timed Out'],401);
        }
        $accId = $check->accId;

        $checkaccId = PTwoPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized'],401);
        }

        $data = CheckoutDetail::where('checkout_id',$checkout_id)->where('accId',$accId)->where('status','0')->first();
        if($data == null){
            return response()->json(['error' => 'Checkout Expired'],401);
        }
        $companyName = $checkaccId->company->company_name;
        $amount = $data->amount;
        $currency = $data->currency;
        $requestId = $data->checkout_id;

        return view('payment.paybis.paybis-payment-page',compact('currency','amount','requestId','companyName'));
    }

     // The method to handle incoming webhooks
     public function handleWebhook(Request $request)
     {
         // Get the raw payload and signature from the request
         $payload = $request->getContent();
         $signature = $request->header('X-Request-Signature');
         Log::info('payload: '.$payload);
         Log::info('signature: '.$signature);
         // Verify the signature
         if (!$this->verifySignature($payload, $signature)) {
             Log::info("Signature Invalid!");
             return response()->json(['error' => 'Its Invalid signature'], 400);
         }
        Log::info("Signature Valid!");
         // Decode the incoming JSON payload
         $data = json_decode($payload, true);

         // Handle different events
         switch ($data['event']) {
             case 'VERIFICATION_STATUS_UPDATED':
                 return $this->handleVerificationStatusUpdated($data['data']);
             case 'TRANSACTION_STATUS_CHANGED':
                 return $this->handleTransactionStatusChanged($data['data']);
             default:
                 Log::warning("Unhandled event: " . $data['event']);
                 return response()->json(['error' => 'Unhandled event'], 200);
         }
     }

     // Method to verify the webhook signature using the public key
     private function verifySignature($payload, $signature)
     {
         $verifier = new RSA();
         $verifier->setSignatureMode(RSA::SIGNATURE_PSS);
         $verifier->setHash('sha512');
         $verifier->setMGFHash('sha512');
         $verifier->setSaltLength(64);
         $verifier->loadKey($this->publicKey);
         Log::info("Signature Verification Process Step: before rsa->verify(). ");

         return $verifier->verify($payload, base64_decode($signature));
     }

     // Handle the Verification Status Update event
     private function handleVerificationStatusUpdated($data)
     {
         $partnerUserId = $data['partnerUserId'];
         $status = $data['status'];
         $residenceCountry = $data['residenceCountry']??'not added';

         if ($status === 'approved') {
             // Mark user as verified

             Log::info("User $partnerUserId : verification approved. Residence Country: $residenceCountry");
             return response()->json(['status' => $status .' for VerificationStatusUpdated']);
         } elseif ($status === 'failed') {
             // Handle verification failure (e.g., notify the user)
             Log::info("User $partnerUserId verification failed.");
             return response()->json(['status' => $status .' for VerificationStatusUpdated']);
         } else {
             // Handle the "started" or other statuses
             Log::info("User $partnerUserId verification status: $status . Residence Country: $residenceCountry");
             return response()->json(['status' => $status .' for VerificationStatusUpdated']);
         }

        //  return response()->json(['status' => $status .' for VerificationStatusUpdated']);
     }

     // Handle the Transaction Status Changed event
     private function handleTransactionStatusChanged($data)
     {
        Log::info('Data for Transaction Status Changed');
         $transactionStatus = $data['transaction']['status'];
         $requestId = $data['requestId'];
         $transaction = $data['transaction'];
         $payment = $data['payment'];

         switch ($transactionStatus) {
             case 'started':
                 Log::info( $data['partnerUserId'] . "\t Transaction $transactionStatus: $requestId .\n
                                        Amount Entered: ". $data['quote']['currencyCodeFrom'] ." ". $data['quote']['amountFrom']['amount'] . "\n
                                        Amount Recieved: ".$data['quote']['amountReceived']['currency']." ". $data['quote']['amountReceived']['amount'] ."\n
                                        To Amount     : ". $data['quote']['currencyCodeTo'] ." ". $data['quote']['amountTo']['amount'] . "\n
                                        Invoice       : ". $transaction['invoice']. ". \t Status Updated  At: ". $transaction['statusUpdatedAt']."\t payment error: ". ($payment['errorCode'] ?? 'null') . "\t
                                        Payment name  : ". $payment['name']
                                        ) ;

                                        $checkoutDetails = CheckoutDetail::where('checkout_id',$requestId)->first();
                                        $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();

                                        $transaction = Transaction::where('checkout_id', $requestId)->first();
                                        if (!$transaction) {
                                            $transaction = new Transaction();
                                        }

                                        $transaction->account_id = $checkoutDetails->accId;
                                        $transaction->currency = strtoupper($data['quote']['amountReceived']['currency']);
                                        $transaction->amount = number_format($data['quote']['amountReceived']['amount'], 4, '.', '');
                                        $transaction->from_currency=  $data['quote']['currencyCodeFrom']; //cuurency user paid in
                                        $transaction->from_amount= $data['quote']['amountFrom']['amount']; //amount user paid in
                                        // $transaction->net_amount= $net_amount;
                                        // $transaction->fees= $fees;
                                        $transaction->checkout_id = $requestId;
                                        $transaction->payment_id = $transaction['invoice'];
                                        $transaction->payment_status = ucfirst($transactionStatus);
                                        $transaction->description = 'Payment for '. $companyDetails->company_name;
                                        $transaction->status = 'p2';
                                        $transaction->save();

                                        $checkoutDetails->status = '0';
                                        $checkoutDetails->save();
                 break;

             case 'completed':
                 Log::info( $data['partnerUserId'] . "\t Transaction $transactionStatus: $requestId .\n
                                        Amount Entered : ". $data['quote']['currencyCodeFrom'] ." ". $data['quote']['amountFrom']['amount'] . "\n
                                        Amount Recieved: ".$data['quote']['amountReceived']['currency']." ". $data['quote']['amountReceived']['amount'] ."\n
                                        To Amount      : ". $data['quote']['currencyCodeTo'] ." ". $data['quote']['amountTo']['amount'] . "\n
                                        Invoice        : ". $transaction['invoice']. ". \t Status Updated  At: ". $transaction['statusUpdatedAt']."\t payment error: ". ($payment['errorCode'] ?? 'null') . "\t
                                        Payment name   : ". $payment['name']
                                        ) ;

                                        $checkoutDetails = CheckoutDetail::where('checkout_id',$requestId)->first();
                                        $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();

                                        $transaction = Transaction::where('checkout_id', $requestId)->first();
                                        if (!$transaction) {
                                            $transaction = new Transaction();
                                        }

                                        $transaction->account_id = $checkoutDetails->accId;
                                        $transaction->currency = strtoupper($data['quote']['amountReceived']['currency']);
                                        $transaction->amount = number_format($data['quote']['amountReceived']['amount'], 4, '.', '');
                                        $transaction->from_currency=  $data['quote']['currencyCodeFrom']; //cuurency user paid in
                                        $transaction->from_amount= $data['quote']['amountFrom']['amount']; //amount user paid in
                                        // $transaction->net_amount= $net_amount;
                                        // $transaction->fees= $fees;
                                        $transaction->checkout_id = $requestId;
                                        $transaction->payment_id = $transaction['invoice'];
                                        $transaction->payment_status = ucfirst($transactionStatus);
                                        $transaction->description = 'Payment for '. $companyDetails->company_name;
                                        $transaction->status = 'p2';
                                        $transaction->save();

                                        $checkoutDetails->status = '1';
                                        $checkoutDetails->save();

                                        try{
                                            $detail = PTwoPaymentMethod::where('accountId',$checkoutDetails->accId)->first();
                                            $headers = [
                                                'Content-Type' => 'application/json',
                                                'Authorization' => $detail->b_token,
                                            ];

                                            $webhook = new Client();
                                            $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p3/'.$requestId, [
                                                'headers' => $headers,
                                            ]);
                                            $statusCode = $resp->getStatusCode();
                                            Log::info("received P2 status-code response. Status code: {$statusCode}");

                                        }
                                        catch(RequestException $e){
                                            Log::warning("P2 response exception catch : ".$e->getMessage());
                                        }
                 break;

             case 'cancelled':
                 Log::info( $data['partnerUserId'] . "\t Transaction $transactionStatus: $requestId .\n
                                        Amount Entered: ". $data['quote']['currencyCodeFrom'] ." ". $data['quote']['amountFrom']['amount'] . "\n
                                        Amount Recieved: ".$data['quote']['amountReceived']['currency']." ". $data['quote']['amountReceived']['amount'] ."\n
                                        To Amount     : ". $data['quote']['currencyCodeTo'] ." ". $data['quote']['amountTo']['amount'] . "\n
                                        Invoice       : ". $transaction['invoice']. ". \t Status Updated  At: ". $transaction['statusUpdatedAt'] ."\t payment error: ". ($payment['errorCode'] ?? 'null') . "\t
                                        Payment name  : ". $payment['name']
                                        ) ;

                                        $checkoutDetails = CheckoutDetail::where('checkout_id',$requestId)->first();
                                        $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();

                                        $transaction = Transaction::where('checkout_id', $requestId)->first();
                                        if (!$transaction) {
                                            $transaction = new Transaction();
                                        }

                                        $transaction->account_id = $checkoutDetails->accId;
                                        $transaction->currency = strtoupper($data['quote']['amountReceived']['currency']);
                                        $transaction->amount = number_format($data['quote']['amountReceived']['amount'], 4, '.', '');
                                        $transaction->from_currency=  $data['quote']['currencyCodeFrom']; //cuurency user paid in
                                        $transaction->from_amount= $data['quote']['amountFrom']['amount']; //amount user paid in
                                        // $transaction->net_amount= $net_amount;
                                        // $transaction->fees= $fees;
                                        $transaction->checkout_id = $requestId;
                                        $transaction->payment_id = $transaction['invoice'];
                                        $transaction->payment_status = ucfirst($transactionStatus);
                                        $transaction->description = 'Payment for '. $companyDetails->company_name;
                                        $transaction->status = 'p2';
                                        $transaction->save();

                                        $checkoutDetails->status = '1';
                                        $checkoutDetails->save();

                                        try{
                                            $detail = PTwoPaymentMethod::where('accountId',$checkoutDetails->accId)->first();
                                            $headers = [
                                                'Content-Type' => 'application/json',
                                                'Authorization' => $detail->b_token,
                                            ];

                                            $webhook = new Client();
                                            $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p3/'.$requestId, [
                                                'headers' => $headers,
                                            ]);
                                            $statusCode = $resp->getStatusCode();
                                            Log::info("received P2 status-code response. Status code: {$statusCode}");

                                        }
                                        catch(RequestException $e){
                                            Log::warning("P2 response exception catch : ".$e->getMessage());
                                        }
                 break;

             case 'rejected':
                 Log::info($data['partnerUserId'] . "\t Transaction $transactionStatus: $requestId . \n Reason: ". $transaction['rejectReason'] . ". \n Invoice: ". $transaction['invoice'] . ". \n Status Updated  At: ". $transaction['statusUpdatedAt']);

                 $checkoutDetails = CheckoutDetail::where('checkout_id',$requestId)->first();
                                        $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();

                                        $transaction = Transaction::where('checkout_id', $requestId)->first();
                                        if (!$transaction) {
                                            $transaction = new Transaction();
                                        }

                                        $transaction->account_id = $checkoutDetails->accId;
                                        $transaction->currency = strtoupper($data['quote']['amountReceived']['currency']);
                                        $transaction->amount = number_format($data['quote']['amountReceived']['amount'], 4, '.', '');
                                        $transaction->from_currency=  $data['quote']['currencyCodeFrom']; //cuurency user paid in
                                        $transaction->from_amount= $data['quote']['amountFrom']['amount']; //amount user paid in
                                        // $transaction->net_amount= $net_amount;
                                        // $transaction->fees= $fees;
                                        $transaction->checkout_id = $requestId;
                                        $transaction->payment_id = $transaction['invoice'];
                                        $transaction->payment_status = ucfirst($transactionStatus);
                                        $transaction->description = 'Payment for '. $companyDetails->company_name;
                                        $transaction->status = 'p2';
                                        $transaction->save();

                                        $checkoutDetails->status = '1';
                                        $checkoutDetails->save();

                                        try{
                                            $detail = PTwoPaymentMethod::where('accountId',$checkoutDetails->accId)->first();
                                            $headers = [
                                                'Content-Type' => 'application/json',
                                                'Authorization' => $detail->b_token,
                                            ];

                                            $webhook = new Client();
                                            $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p3/'.$requestId, [
                                                'headers' => $headers,
                                            ]);
                                            $statusCode = $resp->getStatusCode();
                                            Log::info("received P2 status-code response. Status code: {$statusCode}");

                                        }
                                        catch(RequestException $e){
                                            Log::warning("P2 response exception catch : ".$e->getMessage());
                                        }
                 break;

             case 'payment-error':
                 Log::info($data['partnerUserId'] . "\t Transaction $transactionStatus: $requestId . \n Reason: ". $transaction['rejectReason'] . ". \n Invoice: ". $transaction['invoice'] . ". \n Status Updated  At: ". $transaction['statusUpdatedAt']);
                 $checkoutDetails = CheckoutDetail::where('checkout_id',$requestId)->first();
                                        $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();

                                        $transaction = Transaction::where('checkout_id', $requestId)->first();
                                        if (!$transaction) {
                                            $transaction = new Transaction();
                                        }

                                        $transaction->account_id = $checkoutDetails->accId;
                                        $transaction->currency = strtoupper($data['quote']['amountReceived']['currency']);
                                        $transaction->amount = number_format($data['quote']['amountReceived']['amount'], 4, '.', '');
                                        $transaction->from_currency=  $data['quote']['currencyCodeFrom']; //cuurency user paid in
                                        $transaction->from_amount= $data['quote']['amountFrom']['amount']; //amount user paid in
                                        // $transaction->net_amount= $net_amount;
                                        // $transaction->fees= $fees;
                                        $transaction->checkout_id = $requestId;
                                        $transaction->payment_id = $transaction['invoice'];
                                        $transaction->payment_status = ucfirst($transactionStatus);
                                        $transaction->description = 'Payment for '. $companyDetails->company_name;
                                        $transaction->status = 'p2';
                                        $transaction->save();

                                        $checkoutDetails->status = '1';
                                        $checkoutDetails->save();

                                        try{
                                            $detail = PTwoPaymentMethod::where('accountId',$checkoutDetails->accId)->first();
                                            $headers = [
                                                'Content-Type' => 'application/json',
                                                'Authorization' => $detail->b_token,
                                            ];

                                            $webhook = new Client();
                                            $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p3/'.$requestId, [
                                                'headers' => $headers,
                                            ]);
                                            $statusCode = $resp->getStatusCode();
                                            Log::info("received P2 status-code response. Status code: {$statusCode}");

                                        }
                                        catch(RequestException $e){
                                            Log::warning("P2 response exception catch : ".$e->getMessage());
                                        }

                 break;

             default:
                 Log::warning($data['partnerUserId'] . "\t Unhandled transaction status: $transactionStatus");
                 break;
         }

         return response()->json(['status' => 'success for TransactionStatusChanged: '.$transactionStatus],200);
     }


    //////// X1 PAYMENT PAGE/////////
    public function showX1PaymentPage($checkout_id)
    {
        $date = Carbon::now()->subDays(2);
        CheckoutDetail::where('payment_partner','p3')->where('created_at', '<', $date)->delete();

        $check= CheckoutDetail::where('checkout_id',$checkout_id)->first();
        if($check == null){
            return response()->json(['error' => 'Checkout Timed Out'],401);
        }
        $accId = $check->accId;

        $checkaccId = PThreePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized'],401);
        }

        $data = CheckoutDetail::where('checkout_id',$checkout_id)->where('accId',$accId)->where('status','0')->first();
        if($data == null){
            return response()->json(['error' => 'Checkout Expired'],401);
        }
        $companyName = $checkaccId->company->company_name;
        $account_id = $data->accId;
        $amount =  round($data->amount,2);
        $currency = $data->currency;
        $email = $data->email;
        $checkout_id = $data->checkout_id;
        $widget_id = $checkaccId->widget_id;
        $widget_secret_key = $checkaccId->widget_secret_key;
        $script_url = $checkaccId->script_url;
        $data_address = $checkaccId->data_address;
        $successUrl = $checkaccId->success_url ?? null;
        $errorUrl  = $checkaccId->error_url  ?? null;

        return view('payment.x1.x1-payment-page',compact('currency','amount','email','checkout_id','companyName','account_id','widget_id','widget_secret_key','script_url','data_address','successUrl','errorUrl'));
    }


    /////// STRADAPAY PAYMENT PAGE ///////////
    public function showStradapayPaymentPage($checkout_id)
    {
        $date = Carbon::now()->subDays(3);
        CheckoutDetail::where('payment_partner','p4')->where('created_at', '<', $date)->delete();

        $check= CheckoutDetail::where('checkout_id',$checkout_id)->where('status','0')->first();
        if($check == null){
            return redirect('/error-payment-page');
        }
        $accId = $check->accId;

        $checkaccId = PFourPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized'],401);
        }

        $data = CheckoutDetail::where('checkout_id',$checkout_id)->where('accId',$accId)->where('status','0')->first();
        if($data == null){
            return redirect('/error-payment-page');
        }
        $companyName = $checkaccId->company->company_name;
        $account_id = $data->accId;
        $amount =  round($data->amount,2);
        $currency = $data->currency;
        $email = $data->email;
        $checkout_id = $data->checkout_id;

        $countries = [
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'Canada', 'code' => 'CA'],
            ['name' => 'United Kingdom', 'code' => 'GB'],
            ['name' => 'Australia', 'code' => 'AU'],
            // Add more countries here as needed
        ];

        return view('payment.stradapay.stradapay-payment-page',compact('currency','countries','amount','email','checkout_id','companyName','account_id'));

    }

}
