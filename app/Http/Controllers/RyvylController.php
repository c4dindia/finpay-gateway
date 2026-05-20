<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\Company;
use App\Models\PFivePaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RyvylController extends Controller
{
    protected $bearerToken = "Bearer OGFjN2E0Yzc4YTgxMjU1MzAxOGE4NDgzZjcyOTAzNTl8UXlUaFlMWG1vOCFANThVUGthVGY="; //ryvyl test token
    protected $baseurl = "https://test.payments-ryvyl.eu";

    // protected $bearerToken = "Bearer OGFjZGE0Y2I5MTRmYzY1YzAxOTE1YTM0MGU0YjJiYmV8MnlOeXdEckRwbUptbnRIMw=="; //(Paystrax Token LIVE)
    // protected $baseurl =  "https://eu-prod.oppwa.com";// paystrax production

    ///   API for Ryvyl CHeckout Details  ///
    public function ryvylCheckoutDetail($accId ,Request $request)
    {
        Log::info('requested data: ',$request->all());
         // Get the current time minus 20 minutes
        $date = Carbon::now()->subMinutes(60);

        // Delete records where 'created_at' is older than 20 minutes
        CheckoutDetail::where('payment_partner','p5')->where('created_at', '<', $date)->delete();

        // $checkaccId = Company::where('accountId',$accId)->where('payment_partner','Paystrax')->first();
        $checkaccId = PFivePaymentMethod::where('accountId',$accId)->where('status','1')->first();

        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $amount = $request->amount;
        $currency = strtoupper($request->currency);

       $url = $this->baseurl.'/v1/checkouts';

        if($currency == 'USD'){
            $entityIdz = '8ac9a4cb9242d2fb019247ef4e6000a3'; //for USD(paystrax)
        }
         elseif($currency == 'GBP'){
            $entityIdz = '8ac9a4cb9242d2fb019247f049ea00be'; // for GBP(paystrax)
        }
         elseif($currency == 'EUR'){
            $entityIdz = '8ac7a4c78a812553018a8483f6750355'; //test(ryvyl)
            // $entityIdz = '8ac9a4cb9242d2fb019247efdf5400b0'; //for EUR(paystrax)
        }
         else {
            return  response()->json(['error' => 'This currency is not valid'],402);
        }

        $data = "entityId=".$entityIdz .
                "&amount=".$amount .
                "&currency=".$currency.      //$currency_code
                "&paymentType=DB" .
                "&integrity=true";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:'.$this->bearerToken));
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
        Log::info('Ryvyl checkout created'. json_encode($chkId));
        $checkout_id = $chkId->id;
        $checkout_integrity = $chkId->integrity;

        $checkoutDetailObj = new CheckoutDetail();

        $checkoutDetailObj->accId = $accId;
        $checkoutDetailObj->payment_partner = 'p5';
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
            'link' => 'https://payment.ryzen-pay.com/payment/p5/payment-page/'.$checkoutDetailObj->checkout_id
        ],200);
    }

    public function ryvylGetPaymentStatus($accId,$checkout_id)
    {
        $checkaccId = PFivePaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }
        $checkCheckoutId = Transaction::where('checkout_id',$checkout_id)->where('status','p5')->first();
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

    public function showRyvylPaymentPage($checkout_id)
    {
         // Get the current time minus 29 minutes
         $date = Carbon::now()->subMinutes(60);

         // Delete records where 'created_at' is older than 20 minutes
         CheckoutDetail::where('payment_partner','p5')->where('created_at', '<', $date)->delete();

        $check= CheckoutDetail::where('checkout_id',$checkout_id)->first();
        if($check == null){
           return redirect('/error-payment-page');
            // return response()->json(['error' => 'Checkout Timed Out'],401);
        }
        $accId = $check->accId;

        $checkaccId = PFivePaymentMethod::where('accountId',$accId)->where('status','1')->first();
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

        return view('payment.ryvyl.ryvyl-payment-page',compact('currency','amount','integrity','checkout_id','companyName','created_at'));
    }

    //  to handle Ryvyl payment result and status check
    public function ryvylPaymentResult($checkout_id, Request $request)
    {
        Log::info("ryvyl request data : ");
        Log::info($request->all());
        $checkoutDetails = CheckoutDetail::where('checkout_id',$checkout_id)->first();

        if ($checkoutDetails == null) {
            return  response()->json([
                'error' => 'Unauthorized Access'
            ],401);
        }

        $currency = $checkoutDetails->currency;

        $resourcePath = $request->get('resourcePath');
        $baseUrl = $this->baseurl;

        $url = $baseUrl . $resourcePath;

        if($currency == 'USD'){
            $entityId = '8ac9a4cb9242d2fb019247ef4e6000a3'; //for USD
        } else if($currency == 'GBP'){
            $entityId = '8ac9a4cb9242d2fb019247f049ea00be'; // for GBP
        } else if($currency == 'EUR'){
            $entityId = '8ac7a4c78a812553018a8483f6750355'; //test(ryvyl)
            // $entityId = '8ac9a4cb9242d2fb019247efdf5400b0'; //for EUR(paystrax)
        } else {
            Log::warning("ryvyl request currency invalid in webhook hanndling");
            return  response()->json([
                'error' => 'This currency is not valid'
            ],401);
        }

        $paymentStatus = $this->getRyvylPaymentStatus($url,$entityId);
        Log::info('Ryvyl Payment Status: ' . json_encode($paymentStatus, JSON_PRETTY_PRINT));

        if(isset($paymentStatus['result']) && ($paymentStatus['result']['description'] == 'Transaction succeeded' || $paymentStatus['result']['description'] == "Request successfully processed in 'Merchant in Integrator Test Mode'"))
        {
            $transaction = new Transaction();

            $transaction->currency = strtoupper($paymentStatus['currency']);
            $transaction->amount = number_format($paymentStatus['amount'], 2, '.', '');
            $transaction->from_currency = strtoupper($paymentStatus['currency']);
            $transaction->from_amount = number_format($paymentStatus['amount'], 2, '.', '');
            $transaction->checkout_id = $checkoutDetails->checkout_id;
            $transaction->payment_id = $paymentStatus['id'];
            $transaction->payment_status = 'Succeeded';

            if($checkoutDetails->accId == 'p5/PaymentLink'){
                $transaction->account_id = $checkoutDetails->accId;
                $transaction->description = 'Payment via P5 Payment Link' ;
                $transaction->status = 'p5/PL';
            }else{
                $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();
                $transaction->account_id = $companyDetails->accountId;
                $transaction->description = $paymentStatus['result']['description'];
                $transaction->status = 'p5';
            }

            $transaction->save();

            $checkoutDetails->status = '1';
            $checkoutDetails->save();

            $p5details = PFivePaymentMethod::where('accountId',$companyDetails->accountId)->first();

            if($checkoutDetails->accId == 'p5/PaymentLink'){
                return redirect('/p5/create-payment-link')->with('success','Payment Success!');
            }else{
                return redirect()->to($p5details->redirect_url .'/RyzenPay/p5/checkout-id/'.$transaction->checkout_id);
            }
        }
        else
        {
           $transaction = new Transaction();

            $transaction->currency = strtoupper($paymentStatus['currency']);
            $transaction->amount = number_format($paymentStatus['amount'], 2, '.', '');
            $transaction->from_currency = strtoupper($paymentStatus['currency']);
            $transaction->from_amount = number_format($paymentStatus['amount'], 2, '.', '');
            $transaction->checkout_id = $checkoutDetails->checkout_id;
            $transaction->payment_id = $paymentStatus['id'];
            $transaction->payment_status = 'Failed';

            if($checkoutDetails->accId == 'p5/PaymentLink'){
                $transaction->account_id = $checkoutDetails->accId;
                $transaction->description = 'Payment via P5 Payment Link' ;
                $transaction->status = 'p5/PL';
            }else{
                $companyDetails = Company::where('accountId',$checkoutDetails->accId)->first();
                $transaction->account_id = $companyDetails->accountId;
                $transaction->description = $paymentStatus['result']['description'];
                $transaction->status = 'p5';
            }

            $transaction->save();

            $checkoutDetails->status = '1';
            $checkoutDetails->save();

            $p5details = PFivePaymentMethod::where('accountId',$companyDetails->accountId)->first();

            // if($checkoutDetails->accId == 'p5/PaymentLink'){
            //     return redirect('/p5/create-payment-link')->with('success','Payment Success!');
            // }else{
            //     return redirect()->to($p5details->redirect_url .'/RyzenPay/p5/checkout-id/'.$transaction->checkout_id);
            // }
            dd($transaction);
        }

    }

    //GET payment status endpoint
    private function getRyvylPaymentStatus($url, $entityId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "?entityId=" . $entityId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization:'.$this->bearerToken // bearer token
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
}
