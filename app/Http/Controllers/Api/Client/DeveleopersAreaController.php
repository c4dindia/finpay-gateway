<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\NiobiPayment;
use App\Models\PaytoroPayment;
use App\Models\PEightPaymentMethod;
use App\Models\PFivePaymentMethod;
use App\Models\PFourPaymentMethod;
use App\Models\PNinePaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PSevenPaymentMethod;
use App\Models\PSixPaymentMethod;
use App\Models\PTenPaymentMethod;
use App\Models\PThirteenPaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwelvePaymentMethod;
use App\Models\PTwoPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeveleopersAreaController extends Controller
{
    public function getDelevelopersCredentials()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;

        // Retrieve payment details for each payment method (P1, P2, P3, P4)
        $p1detail = POnePaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p2detail = PTwoPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p3detail = PThreePaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p4detail = PFourPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p5detail = PFivePaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p6detail = PSixPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p7detail = PSevenPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p8detail = PEightPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p9detail = PNinePaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p10detail = PTenPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p11detail = PaytoroPayment::where('accountId', $accId)->where('status','=','1')->first();
        $p12detail = PTwelvePaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p13detail = PThirteenPaymentMethod::where('accountId', $accId)->where('status','=','1')->first();
        $p14detail = NiobiPayment::where('accountId', $accId)->where('status','=','1')->first();

        return response()->json([
            "success" => true,
            "accId"   => $accId,
            "account_name" => Auth::user()->name,
            "services" => [

                'p1detail' => [
                    "name" => "Pay-By-Link(PS)",
                    "bearer_token" => $p1detail?->b_token,
                    "notificational_url" => $p1detail?->redirect_url
                        ? $p1detail->redirect_url . '/RyzenPay/p1/checkout-id/{checkout_id}'
                        : null,
                ],

                'p2detail' => [
                    "name" => "Pay-By-Link(PayB)",
                    "bearer_token" => $p2detail?->b_token,
                    "notificational_url" => $p2detail?->redirect_url
                        ? $p2detail->redirect_url . '/RyzenPay/checkout-id/p2/{checkout_id}'
                        : null,
                ],

                'p3detail' => [
                    "name" => "Pay-By-Link(X)",
                    "bearer_token" => $p3detail?->b_token,
                    "notificational_url" => $p3detail?->redirect_url
                        ? $p3detail->redirect_url . '/api/RyzenPay/p3/{checkout_id}'
                        : null,
                ],

                'p4detail' => [
                    "name" => "Pay-By-Link (Str)",
                    "bearer_token" => $p4detail?->b_token,
                    "notificational_url" => $p4detail?->redirect_url
                        ? $p4detail->redirect_url . '/api/RyzenPay/p4/{checkout_id}'
                        : null,
                ],

                'p5detail' => [
                    "name" => "Pay-By-Link (Ryv)",
                    "bearer_token" => $p5detail?->b_token,
                    "notificational_url" => $p5detail?->redirect_url
                        ? $p5detail->redirect_url . '/api/RyzenPay/p5/{checkout_id}'
                        : null,
                ],

                'p6detail' => [
                    "name" => "Pay-By-Link (TrV)",
                    "bearer_token" => $p6detail?->b_token,
                    "notificational_url" => $p6detail?->redirect_url
                        ? $p6detail->redirect_url . '/api/RyzenPay/p6/{checkout_id}'
                        : null,
                ],

                'p7detail' => [
                    "name" => "Pay-By-Link(PS)",
                    "bearer_token" => $p7detail?->b_token,
                    "notificational_url" => $p7detail?->redirect_url
                        ? $p7detail->redirect_url . '/api/RyzenPay/p7/{checkout_id}'
                        : null,
                ],

                'p8detail' => [
                    "name" => "Pay-By-Link (LQP)",
                    "bearer_token" => $p8detail?->b_token,
                    "notificational_url" => $p8detail?->redirect_url
                        ? $p8detail->redirect_url . '/api/RyzenPay/p8/{checkout_id}'
                        : null,
                ],

                'p8detail_H2H' => [
                    "name" => "Host-2-Host (LQP)",
                    "bearer_token" => $p8detail?->b_token,
                    "notificational_url" => $p8detail?->redirect_url
                        ? $p8detail->redirect_url . '/api/RyzenPay/p8/{checkout_id}'
                        : null,
                ],

                'p9detail' => [
                    "name" => "Pay-By-Link (TrP)",
                    "bearer_token" => $p9detail?->b_token,
                    "notificational_url" => $p9detail?->redirect_url
                        ? $p9detail->redirect_url . '/api/RyzenPay/p9/{checkout_id}'
                        : null,
                ],

                'p10detail' => [
                    "name" => "Crypto POS",
                    "bearer_token" => $p10detail?->b_token,
                    "notificational_url" => $p10detail?->redirect_url
                        ? $p10detail->redirect_url . '/api/RyzenPay/p10/{checkout_id}'
                        : null,
                ],

                'p11detail' => [
                    "name" => "Host-to-Host (FTD)",
                    "bearer_token" => $p11detail?->b_token,
                    "notificational_url" => $p11detail?->redirect_url
                        ? $p11detail->redirect_url . '/api/RyzenPay/p11/{checkout_id}'
                        : null,
                ],

                'p12detail' => [
                    "name" => "Host-to-Host [2D/3D]",
                    "bearer_token" => $p12detail?->b_token,
                    "notificational_url" => $p12detail?->redirect_url
                        ? $p12detail->redirect_url . '/api/RyzenPay/p12/{checkout_id}'
                        : null,
                ],

                'p13detail' => [
                    "name" => "Host-to-Host (Aliz7)",
                    "bearer_token" => $p13detail?->b_token,
                    "notificational_url" => $p13detail?->redirect_url
                        ? $p13detail->redirect_url . '/api/RyzenPay/p13/{checkout_id}'
                        : null,
                ],

                'p14detail' => [
                    "name" => "Pay-By-Link (Niobi)",
                    "bearer_token" => $p14detail?->b_token,
                    "notificational_url" => $p14detail?->redirect_url
                        ? $p14detail->redirect_url . '/api/RyzenPay/p14/{checkout_id}'
                        : null,
                ],
            ]
        ], 200);

    }
}
