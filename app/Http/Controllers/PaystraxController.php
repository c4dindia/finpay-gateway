<?php

namespace App\Http\Controllers;

use App\Models\CheckoutDetail;
use App\Models\POnePaymentMethod;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystraxController extends Controller
{
    public function showPaystraxPaymentCreater(){
        return view('payment.paystrax.paystrax-link-creator');
    }

    public function generatePaystraxPayment(Request $request)
    {
        $date = Carbon::now()->subDays(7);
        CheckoutDetail::where('payment_partner','p1')->where('created_at', '<', $date)->delete();

        $amount = number_format($request->amount,2);
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
        $integrity = $chkId->integrity;

        $checkoutDetailObj = new CheckoutDetail();

        $checkoutDetailObj->accId = 'p1/PaymentLink';
        $checkoutDetailObj->payment_partner = 'p1';
        $checkoutDetailObj->amount = $amount;
        $checkoutDetailObj->currency = $currency;
        $checkoutDetailObj->amount_from = $amount;
        $checkoutDetailObj->checkout_id = $checkout_id;
        $checkoutDetailObj->checkout_integrity = $integrity;
        $checkoutDetailObj->status = '0';

        $checkoutDetailObj->save();

        return view('payment.paystrax.paystrax-link-creator',compact('currency','amount','integrity','checkout_id'));

    }

    //X1 Payment Page for Payment Link
    public function showPaystraxPaymentLink($checkout_id)
    {

       $date = Carbon::now()->subMinutes(20);

       // Delete records where 'created_at' is older than 29 minutes
       CheckoutDetail::where('payment_partner','p1')->where('created_at', '<', $date)->delete();

      $check= CheckoutDetail::where('checkout_id',$checkout_id)->where('status','0')->first();
      if($check == null){
          return redirect('/error-payment-page');
        //   return response()->json(['error' => 'Checkout Timed Out'],401);
      }
      $accId = $check->accId;

      $data = CheckoutDetail::where('checkout_id',$checkout_id)->where('accId',$accId)->where('status','0')->first();
      if($data == null){
          return response()->json(['error' => 'Checkout Expired'],401);
      }
      $companyName = 'P1-PaymentLink';
      $amount = number_format($data->amount,2);
      $currency = $data->currency;
      $checkout_id = $data->checkout_id;
      $integrity = $data->checkout_integrity;

      return view('payment.paystrax.paystrax-payment-page',compact('currency','amount','integrity','checkout_id','companyName'));
    }
}
