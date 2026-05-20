<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\PThreePaymentMethod;
use App\Models\X1Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    //Api
    public function generateX1PaymentResponse(Request $request)
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

        Log::info('Created checkout with p3 payment link api');
        $amount = $request->amount;
        $email = $request->email;
        $currency = $request->currency;
        $firstName = $request->first_name;
        $lastname = $request->last_name;
        $nationality = strtoupper($request->nationality);
        $countryOfResidence = strtoupper($request->country_of_residence);

        $date = Carbon::now()->subDays(7);
        CheckoutDetail::where('payment_partner','p3')->where('created_at', '<', $date)->delete();
        $checkaccId = PThreePaymentMethod::where('b_token',$request->header('Authorization'))->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

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
            $response = $client->post('https://topexch.net/api/register', [
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
                $response2 = $client2->post('https://topexch.net/api/kyc/data', [
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
                'amount' => round($amount,2),
                'currency' => $currency,
                'checkout_id' => $checkout_id ,
                'link' =>  'https://payment.ryzen-pay.com/p3/payment-link/'.$checkout_id,
            ],200);
        }
    }


    //Web Pages
    public function showX1PaymentCreater(){
        return view('payment.x1.x1-link-creator');
    }

    public function generateX1Payment(Request $request)
    {
        $amount = $request->amount;
        $email = $request->email;
        $currency = "EUR";

        $date = Carbon::now()->subDays(7);
        CheckoutDetail::where('payment_partner','p3')->where('created_at', '<', $date)->delete();

        if (empty($amount) || empty($currency) || empty($email)) {
           return response()->json(['error' => 'Incomplete Parameters'], 400);
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

        $checkoutDetailObj->accId = "p3/PaymentLink";
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
            $client = new Client();
            $response = $client->post('https://topexch.net/api/register', [
                'headers' => $headers,
                'json' => $body,
            ]);

            $responseBody = json_decode($response->getBody(), true);
            Log::info('Token for '. $email. ': '.$responseBody['token']);

           if (isset($responseBody['token']))
            {
                Log::info('Inside  if for adding kyc'. $email. ': '.$responseBody['token']);
                $headers2 = [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$responseBody['token'],
                ];

                $body2 = [
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    'nationality' =>  $request->nationality ,
                    'country_of_residence' => $request->country_of_residence ,
                ];

                $client2 = new Client();
                $response2 = $client2->post('https://topexch.net/api/kyc/data', [
                    'headers' => $headers2,
                    'json' => $body2,
                ]);
                Log::info('KYC added for email: '. $request->email);
            }
        } catch (RequestException $e) {
            Log::warning('Request failed or already registered. for email: '. $email);
            Log::warning($e);
        } finally {
            return view('payment.x1.x1-link-creator',compact('checkout_id'));
        }
    }

    //X1 Payment Page for Payment Link
    public function showX1PaymentLink($checkout_id)
    {
        $date = Carbon::now()->subDays(7);
        CheckoutDetail::where('payment_partner','p3')->where('created_at', '<', $date)->delete();

        $check= CheckoutDetail::where('checkout_id',$checkout_id)->first();
        if($check == null){
            return response()->json(['error' => 'Checkout Timed Out'],401);
        }

        $data = CheckoutDetail::where('checkout_id',$checkout_id)->where('status','0')->first();
        if($data == null){
            return response()->json(['error' => 'Checkout Expired'],401);
        }

        $account_id = $data->accId;
        $amount = round($data->amount,2);
        $currency = $data->currency;
        $email = $data->email;
        $checkout_id = $data->checkout_id;

        return view('payment.x1.x1-payment-page',compact('currency','amount','email','checkout_id','account_id'));
    }
}
