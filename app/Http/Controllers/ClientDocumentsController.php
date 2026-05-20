<?php

namespace App\Http\Controllers;

use App\Models\Alikassa;
use App\Models\Company;
use App\Models\Direpay;
use App\Models\NiobiPayment;
use App\Models\PaytoroPayment;
use App\Models\PEighteenPaymentMethod;
use App\Models\PEightPaymentMethod;
use App\Models\PFivePaymentMethod;
use App\Models\PFourPaymentMethod;
use App\Models\PNinePaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PSevenPaymentMethod;
use App\Models\PSixPaymentMethod;
use App\Models\PTenPaymentMethod;
use App\Models\PThirteenPaymentMethod;
use App\Models\PTwoPaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwelvePaymentMethod;
use App\Models\SmilePay;
use App\Models\TrustitBanking;
use App\Models\UniqoPay;
use App\Models\ValensPay;
use App\Models\YaspaBanking;
use Illuminate\Support\Facades\Auth;
use App\Models\UPIPayment;

class ClientDocumentsController extends Controller
{
    public function showDocumentations()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;

        $p1detail = POnePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p2detail = PTwoPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p3detail = PThreePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p4detail = PFourPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p5detail = PFivePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p6detail = PSixPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p7detail = PSevenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p8detail = PEightPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p9detail = PNinePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p10detail = PTenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p11detail = PaytoroPayment::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p12detail = PTwelvePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p13detail = PThirteenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p14detail = NiobiPayment::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p15detail = SmilePay::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p16detail = TrustitBanking::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p17detail = Direpay::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p18detail = PEighteenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p19detail = ValensPay::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        $p20detail = YaspaBanking::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'payin', 'payout', 'status']);
        $p21detail = Alikassa::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);
        $p22detail = UniqoPay::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);
        $p23detail = UPIPayment::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'midv2', 'status']);
        return view('client.documentations', compact(
            'p1detail',
            'p2detail',
            'p3detail',
            'p4detail',
            'p5detail',
            'p6detail',
            'p7detail',
            'p8detail',
            'p9detail',
            'p10detail',
            'p11detail',
            'p12detail',
            'p13detail',
            'p14detail',
            'p15detail',
            'p16detail',
            'p17detail',
            'p18detail',
            'p19detail',
            'p20detail',
            'p21detail',
            'p22detail',
            'p23detail'
        ));
    }

    public function p1DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p1detail = POnePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);
        if(empty($p1detail->status)){
            return redirect()->back()->with('error','You dont have permission to access');
        }else {
            return view('client.p1-docs-page');
        }
    }

    public function p2DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p2detail = PTwoPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        if(empty($p2detail->status)){
            return redirect()->back()->with('error','You dont have permission to access');
        } else {
            return view('client.p2-docs-page');
        }
    }

    public function p3DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p3detail = PThreePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        if(empty($p3detail->status)){
            return redirect()->back()->with('error','You dont have permission to access');
        } else {
            return view('client.p3-docs-page');
        }
    }

    public function p4DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p4detail = PFourPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        if(empty($p4detail->status)){
            return redirect()->back()->with('error','You dont have permission to access');
        } else {
            return view('client.p4-docs-page');
        }
    }

    public function p5DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p5detail = PFivePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        if(empty($p5detail->status)){
            return redirect()->back()->with('error','You dont have permission to access');
        } else {
            return view('client.p5-docs-page');
        }
    }

    public function p6DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p6detail = PSixPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p6detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p6-docs-page');
        // }

        return view('client.p6-docs-page');
    }

    public function p7DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p7detail = PSevenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p7detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p7-docs-page');
        // }

         return view('client.p7-docs-page');
    }

    public function p8DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p8detail = PEightPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p8detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p8-docs-page');
        // }

        return view('client.p8-docs-page');
    }

    public function p8SubscriptionDocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p8detail = PEightPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p8detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p8-docs-page');
        // }

        return view('client.p8-subscription-docs-page');
    }

    public function p9DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p9detail = PNinePaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p9detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p9-docs-page');
        // }

         return view('client.p9-docs-page');
    }

    public function p10DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p10detail = PTenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p10detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p10-docs-page');
        // }

        return view('client.p10-docs-page');
    }

    public function p10PurchaseDocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p10detail = PTenPaymentMethod::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        // if(empty($p10detail->status)){
        //     return redirect()->back()->with('error','You dont have permission to access');
        // } else {
        //     return view('client.p10-docs-page');
        // }

        return view('client.p10-purchase-docs-page');
    }

    public function p14DocPage()
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p14detail = NiobiPayment::where('accountId',$accId)->where('status','=','1')->first(['accountId','status']);

        return view('client.p14-docs-page');
    }

    public function p15DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p15detail = SmilePay::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p15-docs-page');
    }

    public function p16DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p16detail = TrustitBanking::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p16-docs-page');
    }

    public function p17DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p17detail = Direpay::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p17-docs-page');
    }

    public function p18DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p18detail = PEighteenPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p18-docs-page');
    }

    public function p19DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p19detail = ValensPay::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p19-docs-page');
    }

    public function p20DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p20detail = YaspaBanking::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p20-docs-page');
    }

    public function p20PayoutDocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p20detail = YaspaBanking::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p20-payout-docs-page');
    }

    public function p21DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p21detail = Alikassa::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p21-docs-page');
    }
    
    public function p22DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p22detail = UniqoPay::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p22-docs-page');
    }
    
    public function p23DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p23detail = UPIPayment::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p23-docs-page');
    }

    public function p23PayoutDocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p23detail = UPIPayment::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p23-payout-docs-page');
    }
    
    public function p23v2DocPage()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $p23detail = UPIPayment::where('accountId', $accId)->where('status', '=', '1')->first(['accountId', 'status']);

        return view('client.p23-v2-docs-page');
    }
}
