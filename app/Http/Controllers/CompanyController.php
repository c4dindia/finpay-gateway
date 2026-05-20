<?php

namespace App\Http\Controllers;

use App\Models\Alikassa;
use App\Models\Company;
use App\Models\Direpay;
use App\Models\NiobiPayment;
use App\Models\PaytoroPayment;
use App\Models\PEighteenPaymentMethod;
use App\Models\PTenPaymentMethod;
use App\Models\PNinePaymentMethod;
use App\Models\PEightPaymentMethod;
use App\Models\PSevenPaymentMethod;
use App\Models\PSixPaymentMethod;
use App\Models\PFivePaymentMethod;
use App\Models\PFourPaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwoPaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PThirteenPaymentMethod;
use App\Models\PTwelvePaymentMethod;
use App\Models\SmilePay;
use App\Models\Transaction;
use App\Models\TrustitBanking;
use App\Models\UniqoPay;
use App\Models\UpiMerchant;
use App\Models\UPIPayment;
use App\Models\User;
use App\Models\ValensPay;
use App\Models\YaspaBanking;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\UpiPaymentController;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Exception\RequestException;
use Exception;
use App\Imports\UpiMerchantsImport;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    public function addCompany(Request $request)
    {
        do {
            $number = Str::random(28);
        } while (Company::where('accountId', 'ry6hgf43ws' . $number)->exists());
        // $token = Str::random(40);
        // $apikey = Str::random(32);

        $user = User::create([
            'name' => $request->companyName,
            'email' => $request->companyEmail,
            'password' => Hash::make($request->companyPassword),
            'status' => '1',
        ]);

        $company = new Company();
        $company->user_id = $user->id;
        $company->company_name = $request->companyName;
        $company->accountId = 'ry6hgf43ws' . $number;
        $company->email = $request->companyEmail;
        $company->password = $request->companyPassword;
        // $company->payment_partner = $request->companyPayment;
        // $company->redirect_url = $request->companyRedirectURL??null;
        // $company->api_key = $apikey;
        // $company->b_token = 'Bearer '.$token.'==';
        $company->status = '1';

        $company->save();

        return redirect()->back()->with('success', 'Company Added');
    }

    public function assignPMtoComapany(Request $request)
    {
        $company = Company::find($request->selectCompany);

        if ($request->selectPaymentPartner == 'Paystrax') {
            if (POnePaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (POnePaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new POnePaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P1 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Paybis') {
            if (PTwoPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PTwoPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p2 = new PTwoPaymentMethod();

            $p2->accountId = $company->accountId;
            $p2->company_id = $company->id;
            $p2->redirect_url = $request->companyRedirectURL ?? null;
            $p2->api_key = $apikey;
            $p2->b_token = 'Bearer ' . $token . '==';

            $p2->save();

            return redirect()->back()->with('success', 'P2 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'X1') {
            if (PThreePaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PThreePaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p3 = new PThreePaymentMethod();

            $p3->accountId = $company->accountId;
            $p3->company_id = $company->id;
            $p3->redirect_url = $request->companyRedirectURL ?? null;
            $p3->api_key = $apikey;
            $p3->b_token = 'Bearer ' . $token . '==';

            $p3->save();

            return redirect()->back()->with('success', 'P3 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'StradaPay') {
            if (PFourPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PFourPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p4 = new PFourPaymentMethod();

            $p4->accountId = $company->accountId;
            $p4->company_id = $company->id;
            $p4->redirect_url = $request->companyRedirectURL ?? null;
            $p4->api_key = $apikey;
            $p4->b_token = 'Bearer ' . $token . '==';

            $p4->save();

            return redirect()->back()->with('success', 'P4 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Ryvyl') {
            if (PFivePaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PFivePaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PFivePaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P5 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Transvoucher') {
            if (PSixPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PSixPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PSixPaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P6 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'SecurePayZone') {
            if (PSevenPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PSevenPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PSevenPaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P7 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Luqapay') {
            if (PEightPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PEightPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PEightPaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P8 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Trigopay') {
            if (PNinePaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PNinePaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PNinePaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P9 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Inabit') {
            if (PTenPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(32);
            } while (PTenPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PTenPaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P10 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'PaytoroPay') {
            if (PaytoroPayment::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PaytoroPayment::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PaytoroPayment();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P11 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'PGTechPay') {
            if (PTwelvePaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PTwelvePaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PTwelvePaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P12 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Aliz7') {
            if (PThirteenPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PThirteenPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new PThirteenPaymentMethod();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P13 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'NiobiPay') {
            if (NiobiPayment::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (NiobiPayment::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new NiobiPayment();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P14 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'SmilePay') {
            if (SmilePay::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (SmilePay::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new SmilePay();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P15 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'TrustitBanking') {
            if (TrustitBanking::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (TrustitBanking::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new TrustitBanking();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P16 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Direpay') {
            if (Direpay::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (Direpay::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new Direpay();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P17 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Keynexpay') {
            if (PEighteenPaymentMethod::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (PEighteenPaymentMethod::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p18 = new PEighteenPaymentMethod();

            $p18->accountId = $company->accountId;
            $p18->company_id = $company->id;
            $p18->redirect_url = $request->companyRedirectURL ?? null;
            $p18->api_key = $apikey;
            $p18->b_token = 'Bearer ' . $token . '==';

            $p18->save();

            return redirect()->back()->with('success', 'P18 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Valenspay') {
            if (ValensPay::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (ValensPay::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p19 = new ValensPay();

            $p19->accountId = $company->accountId;
            $p19->company_id = $company->id;
            $p19->redirect_url = $request->companyRedirectURL ?? null;
            $p19->api_key = $apikey;
            $p19->b_token = 'Bearer ' . $token . '==';

            $p19->save();

            return redirect()->back()->with('success', 'P19 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'YaspaBanking') {
            if (YaspaBanking::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (YaspaBanking::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new YaspaBanking();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P20 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Alikassa') {
            if (Alikassa::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (Alikassa::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new Alikassa();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P21 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'UniqoPay') {
            if (UniqoPay::where('company_id', $company->id)->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (UniqoPay::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new UniqoPay();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';
            $p1->merchant_api_key = 'pk_prod_cOcicO8IvILra9Bdh9Jw';

            $p1->save();

            return redirect()->back()->with('success', 'P22 Payment Method for ' . $company->company_name);
        } elseif ($request->selectPaymentPartner == 'Upipay') {
            if (UPIPayment::where('company_id', $company->id)->where('status', '1')->exists()) {
                return redirect()->back()->with('error', 'This Service already Added!');
            }

            $apikey = Str::random(32);

            do {
                $token = Str::random(40);
            } while (UPIPayment::where('b_token', 'Bearer ' . $token . '==')->exists());

            $p1 = new UPIPayment();

            $p1->accountId = $company->accountId;
            $p1->company_id = $company->id;
            $p1->redirect_url = $request->companyRedirectURL ?? null;
            $p1->api_key = $apikey;
            $p1->b_token = 'Bearer ' . $token . '==';

            $p1->save();

            return redirect()->back()->with('success', 'P23 Payment Method for ' . $company->company_name);
        } else {
            return redirect()->back()->with('error', 'Payment Method Not Found!');
        }
    }

    public function showRegisterCompany()
    {
        return view('admin.register-company-page');
    }

    public function showAddPaymentService()
    {
        $companies = Company::all();
        return view('admin.add-payment-service-page', compact('companies'));
    }

    public function showPaystraxService()
    {
        $p1co = POnePaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p1services', compact('p1co'));
    }

    public function showPaybisService()
    {
        $p2co = PTwoPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p2services', compact('p2co'));
    }

    public function showX1Service()
    {
        $p3co = PThreePaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p3services', compact('p3co'));
    }

    public function showStradapayService()
    {
        $p4co = PFourPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p4services', compact('p4co'));
    }

    public function showRyvylService()
    {
        $p5co = PFivePaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p5services', compact('p5co'));
    }

    public function showTransvoucherService()
    {
        $p6co = PSixPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p6services', compact('p6co'));
    }

    public function showSecurePayZoneService()
    {
        $p7co = PSevenPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p7services', compact('p7co'));
    }

    public function showLuqapayService()
    {
        $p8co = PEightPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p8services', compact('p8co'));
    }

    public function showTrigopaymentService()
    {
        $p9co = PNinePaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p9services', compact('p9co'));
    }

    public function showInabitService()
    {
        $p10co = PTenPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p10services', compact('p10co'));
    }

    public function showPaytoroPaymentService()
    {
        $p11co = PaytoroPayment::orderBy('created_at', 'desc')->get();
        return view('admin.p11services', compact('p11co'));
    }

    public function showPGTechPayService()
    {
        $p12co = PTwelvePaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p12services', compact('p12co'));
    }

    public function showAliz7Service()
    {
        $p13co = PThirteenPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p13services', compact('p13co'));
    }

    public function showNiobiService()
    {
        $p14co = NiobiPayment::orderBy('created_at', 'desc')->get();
        return view('admin.p14services', compact('p14co'));
    }

    public function showSmileService()
    {
        $p15co = SmilePay::orderBy('created_at', 'desc')->get();
        return view('admin.p15services', compact('p15co'));
    }

    public function showTrustitService()
    {
        $p16co = TrustitBanking::orderBy('created_at', 'desc')->get();
        return view('admin.p16services', compact('p16co'));
    }

    public function showDirepayService()
    {
        $p17co = Direpay::orderBy('created_at', 'desc')->get();
        return view('admin.p17services', compact('p17co'));
    }

    public function showKeynexPayService()
    {
        $p18co = PEighteenPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.p18services', compact('p18co'));
    }

    public function showValensPayService()
    {
        $p19co = ValensPay::orderBy('created_at', 'desc')->get();
        return view('admin.p19services', compact('p19co'));
    }

    public function showYaspaService()
    {
        $p20co = YaspaBanking::orderBy('created_at', 'desc')->get();
        return view('admin.p20services', compact('p20co'));
    }

    public function showAlikassaService()
    {
        $p21co = Alikassa::orderBy('created_at', 'desc')->get();
        return view('admin.p21services', compact('p21co'));
    }

    public function showUniqoPayService()
    {
        $p22co = UniqoPay::orderBy('created_at', 'desc')->get();
        return view('admin.p22services', compact('p22co'));
    }

    public function showUpipayService()
    {
        $p23co = UPIPayment::orderBy('created_at', 'desc')->get();
        $merchants = UpiMerchant::where('status', '1')
            ->get()
            ->groupBy('mid')
            ->map(function ($items, $mid) {
                return [
                    'mid' => $mid,
                    'vpa' => $items->pluck('vpa')->unique()->values()->toArray(),
                ];
            })
            ->values();

        $merchantsv2 = UpiMerchant::where('status', '1')
            ->get()
            ->groupBy('mid')
            ->map(function ($items, $mid) {
                return [
                    'mid' => $mid,
                ];
            })
            ->values();

        return view('admin.p23services', compact('p23co', 'merchants', 'merchantsv2'));
    }

    public function showAllTransactions(Request $request)
    {
        $q       = trim((string) $request->query('q', ''));
        $service = $request->service ?? 'all';

        $query = Transaction::whereNotIn('payment_status', ['Declined', 'Decline', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error', 'Expired', 'Error']);
        if ($service !== 'all') {
            $query->where('status', $service);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('payment_id', 'like', "%{$q}%")
                    ->orWhere('checkout_id', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(50)
            ->appends($request->all());

        return view('admin.all-transactions', compact(
            'transactions',
            'service'
        ));
    }

    public function showAllFailedTransactions(Request $request)
    {
        $q       = trim((string) $request->query('q', ''));
        $service = $request->service ?? 'all';

        $query = Transaction::whereIn('payment_status', ['Declined', 'Decline', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error', 'Expired', 'Error']);
        if ($service !== 'all') {
            $query->where('status', $service);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('payment_id', 'like', "%{$q}%")
                    ->orWhere('checkout_id', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(50)
            ->appends($request->all());

        return view('admin.all-failed-transactions', compact('transactions'));
    }

    public function updateInabitWidgetBalance($id)
    {
        $p10 = PTenPaymentMethod::where('id', $id)->first();
        $widgetId =  $p10->inabit_widget_id ?? null;
        if (!$p10) {
            return back()->with("error", "MID not found");
        }

        try {
            $client = new Client([
                'base_uri' => 'https://api.inabit.biz/v1/',
                'timeout'  => 60,
            ]);
            $response = $client->get('organization/widget/' . $widgetId, [
                'headers' => [
                    'Accept: */*',
                    'Authorization' => 'Bearer 572091c21e53d0ad2d82014d7a9af74e3989aa34e753525367306f08076f0b7e',
                ]
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $data = $body['data'];
            $p10->inabit_widget_balance = $data['balance'];
            $p10->save();

            Log::channel('inabit')->info("Inabit Widget by ID details updated: ", [
                'api_response' => $body
            ]);

            return back()->with("success", "Balance Updated!");
        } catch (\Exception $e) {
            Log::channel('inabit')->error("Error", [
                'error' => $e->getMessage()
            ]);
            return back()->with("info", "try again later!");
        }
    }

    public function updateInabitPurchaseWidgetBalance($id)
    {
        $p10 = PTenPaymentMethod::where('id', $id)->first();
        $widgetId =  $p10->inabit_purchase_widget_id ?? null;
        if (!$p10) {
            return back()->with("error", "MID not found");
        }

        try {
            $client = new Client([
                'base_uri' => 'https://api.inabit.biz/v1/',
                'timeout'  => 60,
            ]);
            $response = $client->get('organization/widget/' . $widgetId, [
                'headers' => [
                    'Accept: */*',
                    'Authorization' => 'Bearer 572091c21e53d0ad2d82014d7a9af74e3989aa34e753525367306f08076f0b7e',
                ]
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $data = $body['data'];
            $p10->inabit_purchase_widget_balance = $data['balance'];
            $p10->save();

            Log::channel('inabit')->info("Inabit Purchase Widget by ID details updated: ", [
                'api_response' => $body
            ]);

            return back()->with("success", "Balance Updated!");
        } catch (\Exception $e) {
            Log::channel('inabit')->error("Error", [
                'error' => $e->getMessage()
            ]);
            return back()->with("info", "try again later!");
        }
    }

    public function editUpipayCompanyDetails(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:u_p_i_payments,id',
            'mid' => 'nullable|string',
            'vpa' => 'nullable|string',
            'midv2' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $upipay = UPIPayment::findOrFail($request->company_id);

        $upipay->update([
            'mid' => $request->mid,
            'vpa' => $request->vpa,
            'midv2' => $request->midv2 ?? null,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Company Details Updated!');
    }

    public function showUpipayMerchants()
    {
        $merchants = UpiMerchant::get()
            ->map(function ($item) {
                $firstTransaction = Transaction::where('status', 'p23')
                    ->where('card_number', $item->vpa)
                    ->oldest('created_at')
                    ->first();

                $lastTransaction = Transaction::where('status', 'p23')
                    ->where('card_number', $item->vpa)
                    ->latest('created_at')
                    ->first();

                $total = Transaction::where('status', 'p23')
                    ->where('card_number', $item->vpa)
                    ->count();

                $completed = Transaction::where('status', 'p23')
                    ->where('payment_status', 'Completed')
                    ->where('card_number', $item->vpa)
                    ->count();

                $item->totalSpends = Transaction::where('status', 'p23')
                    ->where('payment_status', 'Completed')
                    ->where('card_number', $item->vpa)
                    ->sum('amount');
                $item->successRate = $total == 0 ? 0 : ($completed / $total) * 100;
                $item->startDate = $firstTransaction ? $firstTransaction->created_at : null;
                $item->endDate = $lastTransaction ? $lastTransaction->created_at : null;

                return $item;
            });

        return view('admin.p23-merchants', compact('merchants'));
    }

    public function addUpipayMerchant(Request $request)
    {
        UpiMerchant::updateOrCreate([
            'mid' => $request->mid,
            'vpa' => $request->vpa,
        ], [
            'limitPerDay' => $request->limitPerDay,
            'limitPerMonth' => $request->limitPerMonth,
            'limitPerYear' => $request->limitPerYear,
            'status' => $request->status
        ]);

        return back()->with('success', 'Merchant Added!');
    }
    
    public function importMerchants(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        try {
            Excel::import(new UpiMerchantsImport, $request->file('file'));

            return back()->with('success', 'Merchants imported successfully.');
        } catch (\Exception $e) {
            Log::error('Merchant Import Failed', [
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function editUpipayMerchantDetails(Request $request)
    {
        $company = UpiMerchant::where('id', $request->merchant_id)->first();

        if ($company) {
            $company->limitPerDay = $request->limitPerDay;
            $company->limitPerMonth = $request->limitPerMonth;
            $company->limitPerYear = $request->limitPerYear;
            $company->status = $request->status;
            $company->save();

            if ($company->status == '0') {
                $linkedAccounts = UPIPayment::where('mid', $company->mid)->where('vpa', $company->vpa)->get();

                foreach ($linkedAccounts as $account) {
                    $newMerchant = UpiMerchant::where('id', '!=', $company->id)
                        ->where('status', '1')->first();

                    if ($newMerchant) {
                        $account->mid = $newMerchant->mid;
                        $account->vpa = $newMerchant->vpa;
                        $account->status = '1';
                        $account->save();
                    } else {
                        $account->mid = null;
                        $account->vpa = null;
                        $account->status = '0';
                        $account->save();
                    }
                }
            }

            return back()->with('success', 'Merchant Updated!');
        }
        return back()->with('error', 'Merchant Not Found!');
    }
    
    public function deleteUpipayMerchant($id)
    {
        $company = UpiMerchant::where('id', $id)->first();

        if ($company) {
            UPIPayment::where('mid', $company->mid)->where('vpa', $company->vpa)->update([
                'mid' => null,
                'vpa' => null,
                'status' => '0'
            ]);
            
            $company->delete();

            return back()->with('success', 'Merchant Deleted!');
        }
    }
    
    public function deleteAllUpipayMerchants(Request $request)
    {
        $request->validate([
            'merchant_ids' => 'required|array',
            'merchant_ids.*' => 'exists:upi_merchants,id',
        ]);

        UpiMerchant::whereIn('id', $request->merchant_ids)
            ->where('status', '0')
            ->delete();

        return back()->with('success', 'Selected deactivated merchants deleted successfully.');
    }

    public function generateUpipayMerchantLink($merchant_id)
    {
        $merchant = UpiMerchant::where('id', $merchant_id)->first();
        
        $vpaTotalAmount = Transaction::where('card_number', $merchant->vpa)
            ->whereIn('payment_status', ['Pending', 'Completed'])
            ->where('status', 'p23')
            ->sum('amount');

        $vpaLimit = $merchant->limitPerDay ?? 0;

        if ($vpaLimit > 0 && ($vpaTotalAmount + 1) > $vpaLimit) {
            return back()->with('error', 'Daily limit exceeded for VPA: ' . $merchant->vpa);
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientRefId = 'Refx' . uniqid();
        $path = 'https://gatewayeng.azure-api.net/upi/api/1.2/upi/intent/bpm0003/' . $clientRefId;

        $payload = [
            "merchantVpa" => $merchant->vpa,
            "mid" => $merchant->mid,
            "amount" => "1",
            "note" => 'Test Payment',
            "clientRefId" => $clientRefId,
            "expiryValue" => "15"
        ];

        $upiController = app(UpiPaymentController::class);
        $accessToken = $upiController->accessToken();
        $checksum = $upiController->generateChecksum($payload);

        if (!$accessToken || !$checksum) {
            return back()->with('error', 'Access Token or Checksum not found.');
        }

        $client = new Client();
        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => '1ceb19d850404bac9ae417b1ba0a4191',
                    'Authorization' => 'Bearer ' . $accessToken,
                    'ChannelID' => 'TG2',
                ],
                'json' => [
                    "payload" => $payload,
                    "checksum" => $checksum
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                $paymentData = str_replace(' ', '', $data['response']['intentUrl']);
                $type = 'INTENT';

                $token = Crypt::encryptString(json_encode([
                    'checkout_id' => $uuid,
                    'type' => $type,
                    'data' => $paymentData,
                    'expires_at' => now()->addMinutes(15)->timestamp,
                ]));

                $payUrl = route('p23.payment.page', [
                    'checkout_id' => $uuid,
                ]) . '?' . http_build_query([
                    'token' => $token,
                ]);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = 'ry6hgf43ws6u7bi8cczqNVKXj4Aix2nWIkCVqi';
                $trans->currency       = 'INR';
                $trans->amount         = '1';
                $trans->checkout_id    = $uuid;
                $trans->payment_id      = $data['txnId'];
                $trans->payment_status = 'Pending';
                $trans->description     = 'Test Payment';
                $trans->card_number     = $merchant->vpa;
                $trans->status         = 'p23';
                $trans->customer_details  = $clientRefId;
                $trans->save();

                Log::info("UPI: Payin Initialization with Checkout ID:- " . $uuid);

                return redirect($payUrl);
            } else {
                Log::error('UPI: Checkout Request Failed:- ' . ($data['errorMsg']));
                return back()->with('error', 'Failed to create checkout: ' . ($data['errorMsg']));
            }
        } catch (RequestException $e) {
            Log::error('UPI: Checkout Creation Failed:- ' . $e->getMessage());
            return back()->with('error', 'Failed to create checkout');
        } catch (Exception $e) {
            Log::error('UPI: Checkout Creation Failed:- ' . $e->getMessage());
            return back()->with('error', 'Failed to create checkout');
        }
    }
}
