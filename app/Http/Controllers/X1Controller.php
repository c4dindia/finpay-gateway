<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\PThreePaymentMethod;
use App\Models\Transaction;
use App\Models\X1Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class X1Controller extends Controller
{
    public function getX1Checkoutdetail(Request $request, $accId)
    {
        $x1countryCodes = [
            "ALB", "DZA", "AND", "AGO", "AIA", "ATG", "ARG", "ARM", "AUS", "AUT", "AZE",
            "BHS", "BHR", "BGD", "BRB", "BEL", "BLZ", "BEN", "BMU", "BTN", "BOL", "BIH",
            "BWA", "BVT", "BRA", "IOT", "BRN", "BGR", "KHM", "CPV", "CYM", "CHL", "CHN",
            "CXR", "CCK", "COL", "COK", "CRI", "HRV", "CYP", "CZE", "DNK", "DJI", "DMA",
            "DOM", "ECU", "EGY", "SLV", "GNQ", "EST", "ETH", "FLK", "FRO", "FJI", "FIN",
            "FRA", "FXX", "GUF", "ATF", "GAB", "GEO", "DEU", "GHA", "GIB", "GGY", "GRC",
            "GRL", "GRD", "GLP", "GTM", "GIN", "GUY", "HMD", "HND", "HKG", "HUN", "ISL",
            "IND", "IMN", "IDN", "IRL", "ISR", "ITA", "JEY", "JAM", "JOR", "KAZ", "KEN",
            "KOR", "XKX", "KWT", "KGZ", "LVA", "LSO", "LIE", "LTU", "LUX", "MAC", "MKD",
            "MDG", "MWI", "MYS", "MDV", "MLT", "MTQ", "MRT", "MUS", "MYT", "MEX", "MDA",
            "MCO", "MNG", "MNE", "MSR", "MAR", "NRU", "NPL", "NCL", "NZL", "NIC", "NIU",
            "NFK", "NOR", "OMN", "PAK", "PLW", "PAN", "PRY", "PER", "PHL", "PCN", "POL",
            "PRT", "QAT", "REU", "ROU", "RWA", "KNA", "LCA", "VCT", "WSM", "SMR", "STP",
            "SAU", "SEN", "SRB", "SYC", "SLE", "SGP", "SVK", "SVN", "ZAF", "SGS", "ESP",
            "LKA", "SHN", "SPM", "SUR", "SJM", "SWZ", "SWE", "CHE", "TWN", "TZA", "THA",
            "TGO", "TKL", "TON", "TTO", "TUN", "TUR", "TKM", "TCA", "UGA", "UKR", "ARE",
            "GBR", "URY", "UZB", "VUT", "VAT", "VNM", "VGB", "WLF", "ZMB", "CUW"
        ];
        Log::info('Created checkout with p3 payment api');
        $date = Carbon::now()->subDays(7);
        CheckoutDetail::where('payment_partner','p3')->where('created_at', '<', $date)->delete();

        $checkaccId = PThreePaymentMethod::where('accountId',$accId)->where('status','1')->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $amount = $request->amount;
        $email = $request->email;
        $currency = strtoupper($request->currency);
        $firstName = $request->first_name;
        $lastname = $request->last_name;
        $nationality = strtoupper($request->nationality);
        $countryOfResidence = strtoupper($request->country_of_residence);

        if (empty($amount) || empty($currency) || empty($email) || empty($firstName) || empty($lastname) || empty($nationality) || empty($countryOfResidence)) {
           return response()->json(['error' => 'Incomplete Parameters'], 400);
        }
        if (!in_array(strtoupper($nationality), $x1countryCodes)) {
            Log::info("they entered nationality code as: ". $nationality);
            return response()->json(['error' => 'Invalid nationality code. Should be 3 Letter country code'], 400);
        }
        if (!in_array(strtoupper($countryOfResidence), $x1countryCodes)) {
            Log::info("they entered nationality code as: ". $countryOfResidence);
            return response()->json(['error' => 'Invalid country Of Residence code. Should be 3 Letter country code'], 400);
        }

        do {
            $uuid = \Illuminate\Support\Str::uuid()->toString(); // Generate UUID
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

        $checkoutDetailObj->accId = PThreePaymentMethod::where('b_token',$request->header('Authorization'))->first()->accountId;
        $checkoutDetailObj->payment_partner = 'p3';
        $checkoutDetailObj->amount =  $amount;
        $checkoutDetailObj->currency = $currency;
        $checkoutDetailObj->amount_from = $amount;
        $checkoutDetailObj->currency_from = $currency;
        $checkoutDetailObj->email = $email;
        $checkoutDetailObj->checkout_id = $uuid;
        $checkoutDetailObj->status = '0';

        $checkoutDetailObj->save();

        $checkout_id = $checkoutDetailObj->checkout_id;

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $body = [
            'email' => $email,
            'locale' => 'en',
        ];

        try {
            Log::info('request from acc. Id : '.PThreePaymentMethod::where('b_token',$request->header('Authorization'))->first()->accountId);
            $client = new Client();
            $response = $client->post('https://'.$checkaccId->script_url.'/api/register', [
                'headers' => $headers,
                'json' => $body,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('Token for '. $email. ': '.$responseBody['token']);

           if (isset($responseBody['token']))
            {
                $x1token = new X1Token();
                $x1token->token = $responseBody['token'];
                $x1token->email = $email;
                $x1token->widget_id = $checkaccId->widget_id;
                $x1token->save();

                Log::info('Inside  if for adding kyc'. $email. ': '.$responseBody['token']);
                $headers2 = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$responseBody['token'],
                ];

                $body2 = [
                    "first_name" => $firstName,
                    "last_name" => $lastname,
                    'nationality' =>  $nationality ,
                    'country_of_residence' => $countryOfResidence ,
                ];

                $client2 = new Client();
                $response2 = $client2->post('https://'.$checkaccId->script_url.'/api/kyc/data', [
                    'headers' => $headers2,
                    'json' => $body2,
                ]);
                Log::info('KYC added for email: '. $email);
            }
        } catch (RequestException $e) {
            Log::warning('Request failed or already registered. for email: '. $email);
            Log::warning($e);
        } finally {
            return response()->json([
                'amount' => $amount,
                'currency'=> $currency,
                'checkout_id' => $checkout_id ,
                'link' =>  'https://payment.ryzen-pay.com/payment/p3/payment-page/'.$checkout_id,
            ],200);
        }

    }

    public function x1GetPaymentStatus($accId, $checkout_id)
    {
        // Log::info('Called p3 payment status api');
        $checkaccId = PThreePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $checkCheckoutId = Transaction::where('checkout_id',$checkout_id)->where('status','p3')->first();
        if($checkCheckoutId == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        return response()->json([
            'data' => [
                // "account_id" => $checkCheckoutId->account_id,
                "currency" => $checkCheckoutId->currency,
                "amount" => $checkCheckoutId->amount,
                "from_currency" => $checkCheckoutId->from_currency,
                "from_amount" => $checkCheckoutId->from_amount,
                "fees" => $checkCheckoutId->fees??'',
                "checkout_id" => $checkCheckoutId->checkout_id,
                "payment_id" => $checkCheckoutId->payment_id,
                "payment_status" => ucfirst($checkCheckoutId->payment_status),
                "description" => $checkCheckoutId->description,
                "created_at" => $checkCheckoutId->created_at,
            ]
        ],200);
    }

    public function handleNotification(Request $request)
    {
        $data = $request->all();
        Log::info('Live X1 Notification: ', $data);

        // if($data['deposits'][0]['status'] == 'completed' && Transaction::where('payment_id',$data['deposits'][0]['transactionNumber'])->exists()){
        //     return response()->json(['status' => 'success'], 200);
        // }

        $signature = $data['signature'] ?? null;
        $orderNumber = $data['orderNumber'] ?? null;
        // $secret = 'RSWjSEwzTc2XFy6t';

        $secretKeys = ['RSWjSEwzTc2XFy6t', 'kkUpcZz7HZVOAFmH' , 'JmJPXIgVga5sfmgO']; // [ HowToPay(Common), Jensen, LDDP]
        $secret = null;

        foreach ($secretKeys as $sKey) {
            $expectedSignature = hash('sha256', $orderNumber . $sKey);

            if ($signature === $expectedSignature) {
                $secret = $sKey;
                break;
            }
        }

        if (!$secret) {
            Log::warning('Signature verification failed for all secrets');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if( $data['deposits'][0]['status'] == 'completed')
        {
            $email = $data['user']['email'];
            $transactionNumber = $data['deposits'][0]['transactionNumber'];
            $status = $data['deposits'][0]['status'];
            $amount = $data['deposits'][0]['amount'];
            $net_amount = $data['deposits'][0]['netAmount'];
            $fees = $data['deposits'][0]['fees'];

            $baseCurrencySymbol = $data['orders'][0]['pair']['baseCurrency']['symbol'];
            $orderAmount = $data['orders'][0]['amount'];

            $quoteCurrencySymbol = $data['orders'][0]['pair']['quoteCurrency']['symbol'];
            $orderOrderNumber = $data['orders'][0]['orderNumber'];
            $payload = json_decode($data['payload'], true);

            $transaction = Transaction::where('payment_id', $transactionNumber)->first() ?: new Transaction();
            $transaction->account_id= $payload['account_id'];
            $transaction->currency= $baseCurrencySymbol; // currency in which we recieved in
            $transaction->amount= $orderAmount;  // amount in which we recieved in
            $transaction->from_currency=  $quoteCurrencySymbol; //cuurency user paid in
            $transaction->from_amount= $amount; //amount user paid in
            $transaction->net_amount= $net_amount;
            $transaction->fees= $fees;
            $transaction->checkout_id= $payload['checkout_id'];
            $transaction->payment_id= $transactionNumber;
            $transaction->payment_status= ucfirst($status);

            if ($payload['account_id'] == 'p3/PaymentLink')
            {
                $transaction->description= 'OrdNo# ' .$orderOrderNumber.' Payment Link for '. $email;
                $transaction->status= 'p3/PL';
            }else{
                $company_name = PThreePaymentMethod::where('accountId',$payload['account_id'])->first();
                $transaction->description= 'OrdNo# ' .$orderOrderNumber;
                $transaction->status= 'p3';
            }

            $transaction->save();

            $chkout = CheckoutDetail::where('checkout_id',$payload['checkout_id'])->first();
            $chkout->status = "1";
            $chkout->save();
        }
        else{

            $email = $data['user']['email'];
            $transactionNumber = $data['deposits'][0]['transactionNumber'];
            $status = $data['deposits'][0]['status'];
            $amount = $data['deposits'][0]['amount'];
            $currency = $data['deposits'][0]['currency']['symbol'];
            $net_amount = $data['deposits'][0]['netAmount'];
            $fees = $data['deposits'][0]['fees'];

            $payload = json_decode($data['payload'], true);

            if (isset($data['deposits'][0]['messages']['acquirerResponse']['message'])) {
                $message = $data['deposits'][0]['messages']['acquirerResponse']['message'];
            } elseif (isset($data['deposits'][0]['messages']['schemeResponse']['message'])) {
                $message = $data['deposits'][0]['messages']['schemeResponse']['message'];
            } elseif (isset($data['deposits'][0]['messages']['schemeElectronicCommerceIndicator']['message'])) {
                $message = $data['deposits'][0]['messages']['schemeElectronicCommerceIndicator']['message'];
            } else {
                $message = 'No message found.';
            }

            $transaction = Transaction::where('payment_id', $transactionNumber)->first() ?: new Transaction();
            $transaction->account_id= $payload['account_id'];
            $transaction->currency= $currency; // currency in which we recieved in
            $transaction->amount= $amount;  // amount in which we recieved in
            $transaction->from_currency=  $currency; //currency user paid in
            $transaction->from_amount= $amount; //amount user paid in
            $transaction->net_amount= $net_amount;
            $transaction->fees= $fees;
            $transaction->checkout_id= $payload['checkout_id'];
            $transaction->payment_id= $transactionNumber;
            $transaction->payment_status= ucfirst($status);

            if ($payload['account_id'] == 'p3/PaymentLink')
            {
                $transaction->description = 'Payment Link for '. $email.': '. $message;
                $transaction->status= 'p3/PL';
            }else{
                $company_name = PThreePaymentMethod::where('accountId',$payload['account_id'])->where('status','1')->first();
                $transaction->description = $message;
                $transaction->status= 'p3';
            }

            $transaction->save();

            $chkout = CheckoutDetail::where('checkout_id',$payload['checkout_id'])->first();
            $chkout->status = "1";
            $chkout->save();
        }

        if($payload['account_id'] != 'p3/PaymentLink')
        {
            try{
                $detail = PThreePaymentMethod::where('accountId',$payload['account_id'])->first();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => $detail->b_token,
                ];

                $webhook = new Client();
                $resp = $webhook->get($detail->redirect_url.'/api/RyzenPay/p3/'.$payload['checkout_id'], [
                    'headers' => $headers,
                ]);
                $statusCode = $resp->getStatusCode();
                Log::info("received p3 status-code response. Status code: {$statusCode}");

            }
            catch(RequestException $e){
                Log::warning("response exception catch : ".$e->getMessage());
            }
            finally{
                return response()->json(['status' => 'success'], 200);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    //X1 host-to-host using Create widget order
    public function getX1HostToHost(Request $request, $accId)
    {
        Log::info('Created checkout with p3 host-to-Host Api');
        $date = Carbon::now()->subDays(7);
        CheckoutDetail::where('payment_partner','p3')->where('created_at', '<', $date)->delete();

        $checkaccId = PThreePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        //added parameters
        $widget_secret_key= $checkaccId->widget_secret_key ?? "RSWjSEwzTc2XFy6t";
        $widget_id	=  $checkaccId->widget_id ?? "W1KGL0QQ";
        $base_url =  $checkaccId->script_url ?? "topexch.net";
        $data_address = $checkaccId->data_address;

        $x1countryCodes = [
            "ALB", "DZA", "AND", "AGO", "AIA", "ATG", "ARG", "ARM", "AUS", "AUT", "AZE",
            "BHS", "BHR", "BGD", "BRB", "BEL", "BLZ", "BEN", "BMU", "BTN", "BOL", "BIH",
            "BWA", "BVT", "BRA", "IOT", "BRN", "BGR", "KHM", "CPV", "CYM", "CHL", "CHN",
            "CXR", "CCK", "COL", "COK", "CRI", "HRV", "CYP", "CZE", "DNK", "DJI", "DMA",
            "DOM", "ECU", "EGY", "SLV", "GNQ", "EST", "ETH", "FLK", "FRO", "FJI", "FIN",
            "FRA", "FXX", "GUF", "ATF", "GAB", "GEO", "DEU", "GHA", "GIB", "GGY", "GRC",
            "GRL", "GRD", "GLP", "GTM", "GIN", "GUY", "HMD", "HND", "HKG", "HUN", "ISL",
            "IND", "IMN", "IDN", "IRL", "ISR", "ITA", "JEY", "JAM", "JOR", "KAZ", "KEN",
            "KOR", "XKX", "KWT", "KGZ", "LVA", "LSO", "LIE", "LTU", "LUX", "MAC", "MKD",
            "MDG", "MWI", "MYS", "MDV", "MLT", "MTQ", "MRT", "MUS", "MYT", "MEX", "MDA",
            "MCO", "MNG", "MNE", "MSR", "MAR", "NRU", "NPL", "NCL", "NZL", "NIC", "NIU",
            "NFK", "NOR", "OMN", "PAK", "PLW", "PAN", "PRY", "PER", "PHL", "PCN", "POL",
            "PRT", "QAT", "REU", "ROU", "RWA", "KNA", "LCA", "VCT", "WSM", "SMR", "STP",
            "SAU", "SEN", "SRB", "SYC", "SLE", "SGP", "SVK", "SVN", "ZAF", "SGS", "ESP",
            "LKA", "SHN", "SPM", "SUR", "SJM", "SWZ", "SWE", "CHE", "TWN", "TZA", "THA",
            "TGO", "TKL", "TON", "TTO", "TUN", "TUR", "TKM", "TCA", "UGA", "UKR", "ARE",
            "GBR", "URY", "UZB", "VUT", "VAT", "VNM", "VGB", "WLF", "ZMB", "CUW"
        ];

        $amount = $request->amount;
        $email = $request->email;
        $currency = strtoupper($request->currency);
        $firstName = $request->first_name;
        $lastname = $request->last_name;
        $nationality = strtoupper($request->nationality);
        $countryOfResidence = strtoupper($request->country_of_residence);
        $card_number = $request->card_number;
        $card_holder = $request->card_holder;
        $exp_date = $request->exp_date;
        $cvv = $request->cvv;
        $to_currency = $request->to_currency;

        if (empty($amount) || empty($currency) || empty($email) || empty($firstName) || empty($lastname) || empty($nationality) || empty($countryOfResidence) || empty($card_number) || empty($card_holder) || empty($exp_date) || empty($cvv) || empty($to_currency) ) {
           return response()->json(['error' => 'Incomplete Parameters'], 400);
        }
        if (!in_array(strtoupper($nationality), $x1countryCodes)) {
            Log::info("they entered nationality code as: ". $nationality);
            return response()->json(['error' => 'Invalid nationality code. Should be 3 Letter country code'], 400);
        }
        if (!in_array(strtoupper($countryOfResidence), $x1countryCodes)) {
            Log::info("they entered nationality code as: ". $countryOfResidence);
            return response()->json(['error' => 'Invalid country Of Residence code. Should be 3 Letter country code'], 400);
        }

        do {
            $uuid = \Illuminate\Support\Str::uuid()->toString(); // Generate UUID
        } while (CheckoutDetail::where('checkout_id', $uuid)->exists()); // Check if UUID exists

        $checkoutDetailObj = new CheckoutDetail();

        $checkoutDetailObj->accId = PThreePaymentMethod::where('b_token',$request->header('Authorization'))->first()->accountId;
        $checkoutDetailObj->payment_partner = 'p3';
        $checkoutDetailObj->amount =  $amount;
        $checkoutDetailObj->currency = $currency;
        $checkoutDetailObj->amount_from = $amount;
        $checkoutDetailObj->currency_from = $currency;
        $checkoutDetailObj->email = $email;
        $checkoutDetailObj->checkout_id = $uuid;
        $checkoutDetailObj->status = '0';

        $checkoutDetailObj->save();

        $checkout_id = $checkoutDetailObj->checkout_id;

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $body = [
            'email' => $email,
            'locale' => 'en',
        ];

        try {
            Log::info('request from acc. Id : '.PThreePaymentMethod::where('b_token',$request->header('Authorization'))->first()->accountId);
            $client = new Client();
            $response = $client->post('https://'.$base_url.'/api/register', [
                'headers' => $headers,
                'json' => $body,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('Token for '. $email. ': '.$responseBody['token']);

           if (isset($responseBody['token']))
            {
                $x1token = new X1Token();
                $x1token->token = $responseBody['token'];
                $x1token->email = $email;
                $x1token->widget_id = $checkaccId->widget_id;
                $x1token->save();

                Log::info('Inside  if for adding kyc'. $email. ': '.$responseBody['token']);
                $headers2 = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$responseBody['token'],
                ];

                $body2 = [
                    "first_name" => $firstName,
                    "last_name" => $lastname,
                    'nationality' =>  $nationality ,
                    'country_of_residence' => $countryOfResidence ,
                ];

                $client2 = new Client();
                $response2 = $client2->post('https://'.$base_url.'/api/kyc/data', [
                    'headers' => $headers2,
                    'json' => $body2,
                ]);
                Log::info('KYC added for email: '. $email);
            }
        } catch (RequestException $e) {
            Log::warning('Request failed or already registered. for email: '. $email);
            Log::warning($e);
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();

                $responseJson = json_decode($responseBody, true);
                $emailErrors = $responseJson['errors']['email'] ?? [];

                foreach ($emailErrors as $msg) {
                    if (stripos($msg, 'The email has already been taken.') === false) {
                        return response()->json([
                        'status' => false,
                        'message' => $msg,
                    ], 422);
                    }
                }
            }
        } finally {
           Log::info('Register and  Kyc api part done.');
        }

        //widget order api
        $tokenUser = X1Token::where('email',$email)->where('widget_id',$checkaccId->widget_id)->first();
        $headers3 = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $tokenUser->token,
        ];

        $body3 = [
            "email" => $email,
            "first_name" => $firstName,
            "last_name" => $lastname,
            'nationality' =>  $nationality ,
            'country_of_residence' => $countryOfResidence ,
            "amount" => $amount,
            "card_number" => $card_number,
            "card_holder" => $card_holder,
            "exp_date" => $exp_date,
            "cvv" => $cvv,
            "from_currency" => $currency,
            "to_currency" => $to_currency,
            "widget_number" => $widget_id,
            "success_url" => $request->success_url?? "https://ryzen-pay.com",
            "error_url" => $request->error_url ?? "https://ryzen-pay.com",
            "own_account" =>  "yes",
            "form_declaration" => "yes",
            "user_agent" => $request->header('User-Agent'),
            "payload" =>  '{ "account_id": "'.$accId.'", "checkout_id": "'.$checkout_id .'" }', //'{ \"account_id\": \"' . $accId . '\", \"checkout_id\": \"' . $checkout_id . '\" }'
            // "to_method" => $request->to_method ?? "ETH",
            "address" => $data_address,
            "language" => "en",
        ];

        //to method = "ETH"
        if(in_array($widget_id,["WHLTDVOJ","WKEIKPGL"])){
            $body3['to_method'] =  $request->to_method ?? "ETH";
        }

        $client3 = new Client();
        try{
            $response3 = $client3->post('https://'.$base_url.'/api/widget-orders', [
                'headers' => $headers3,
                'json' => $body3,
            ]);

            $statusCode3 = $response3->getStatusCode();
            $responseBody3 = json_decode($response3->getBody()->getContents(), true);

            if ($statusCode3 === 200) {
                if (isset($responseBody3['redirect_url'])) {
                    Log::info('Status Code: 200 ( 3DS is required) and redirect URL'. $responseBody3['redirect_url']);
                    return response()->json([
                        'message' => '3DS authentication required',
                        'checkout_id' => $checkout_id,
                        'redirect_url' => $responseBody3['redirect_url']
                    ]);
                } else {
                    Log::info('Status Code: 200 and no redirect URL. As its non-3DS');
                    return response()->json([
                        'message' => 'Transaction successful',
                        'checkout_id' => $checkout_id,
                    ]);
                }
            }
        }catch(RequestException $e){
            Log::warning('Request failed while calling X1 Widget Order API. for email: '. $email);
            Log::warning($e);

            if ($e->hasResponse()) {
                $response3 = $e->getResponse();
                $statusCode3 = $response3->getStatusCode();
                $body3 = json_decode($response3->getBody()->getContents(), true);

                if ($statusCode3 === 422) {
                    if (isset($body3['status']) && $body3['status'] === 'failed') {
                        return response()->json([
                            'error' => 'Transaction declined by payment processor',
                            'checkout_id' => $checkout_id,
                            'details' => $body3['messages']['acquirerResponse'] ?? null
                        ], 422);
                    } elseif (isset($body3['errors'])) {
                        return response()->json([
                            'error' => 'Validation error',
                            'checkout_id' => $checkout_id,
                            'fields' => $body3['errors']
                        ], 422);
                    }
                } elseif ($statusCode3 === 500) {
                    return response()->json([
                        'error' => 'Internal server error',
                        'checkout_id' => $checkout_id,
                        'message' => $body3['message'] ?? 'Something went wrong on the server.'
                    ], 500);
                }
            }

            return response()->json([
                'error' => 'An error occurred while processing the request.',
                'checkout_id' => $checkout_id,
            ], 500);
        }
    }
}
