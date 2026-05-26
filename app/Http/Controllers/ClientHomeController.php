<?php

namespace App\Http\Controllers;

use App\Models\Alikassa;
use App\Models\AmountSettlement;
use App\Models\Company;
use App\Models\Direpay;
use App\Models\NiobiPayment;
use App\Models\PaytoroPayment;
use App\Models\PEighteenPaymentMethod;
use App\Models\PTenPaymentMethod;
use App\Models\PEightPaymentMethod;
use App\Models\PFivePaymentMethod;
use App\Models\PFourPaymentMethod;
use App\Models\PNinePaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PSevenPaymentMethod;
use App\Models\PSixPaymentMethod;
use App\Models\PThirteenPaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwelvePaymentMethod;
use App\Models\PTwoPaymentMethod;
use App\Models\SmilePay;
use App\Models\Transaction;
use App\Models\TrustitBanking;
use App\Models\ValensPay;
use App\Models\UniqoPay;
use App\Models\YaspaBanking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UPIPayment;

class ClientHomeController extends Controller
{
    public function showHome(Request $request)
    {
        if ($request->has('service')) {
            if ($request->service === 'all') {
                session()->forget('service');
            } else {
                session(['service' => $request->service]);
            }
            return back();   // reload page
        }

        $serviceFilter = session('service', 'all');
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;

        $baseQuery = Transaction::where('account_id', $accId);
        if ($serviceFilter != 'all') {
            $baseQuery = $baseQuery->where('status', $serviceFilter);
        }

        $gbpTotal = (clone $baseQuery)->where('currency', 'GBP')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $usdTotal = (clone $baseQuery)->where('currency', 'USD')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $usdtTotal = (clone $baseQuery)->where('currency', 'USDT')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $ethTotal = (clone $baseQuery)->where('currency', 'ETH')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $eurTotal = (clone $baseQuery)->where('currency', 'EUR')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $cadTotal = (clone $baseQuery)->where('currency', 'CAD')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $inrTotal = (clone $baseQuery)->where('currency', 'INR')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'])
            ->sum('amount');

        $approvedStatuses = ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid'];

        // -------- TABLE TRANSACTIONS (needed before chart currency is finalized) --------
        $totalTransactions = $baseQuery->get();

        $chartCurrencyOptions = $totalTransactions
            ->pluck('currency')
            ->map(fn($c) => strtoupper(trim((string) $c)))
            ->filter()
            ->unique()
            ->values();

        $chartCurrency = strtoupper(trim((string) $request->input('chartCurrency', '')));
        if ($chartCurrency === '') {
            $approvedCurrencies = $totalTransactions
                ->whereIn('payment_status', $approvedStatuses)
                ->pluck('currency')
                ->map(fn($c) => strtoupper(trim((string) $c)))
                ->filter()
                ->unique()
                ->values();

            $chartCurrency = $approvedCurrencies->contains('INR')
                ? 'INR'
                : ($approvedCurrencies->first() ?? 'INR');
        }

        if (! $chartCurrencyOptions->contains($chartCurrency)) {
            $chartCurrency = $chartCurrencyOptions->first() ?? $chartCurrency;
        }

        // -------- CHART DATA --------
        $chartQuery = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
            ->whereYear('created_at', date("Y"))
            ->whereRaw('UPPER(currency) = ?', [$chartCurrency])
            ->where('account_id', $accId)
            ->whereIn('payment_status', $approvedStatuses);

        if ($serviceFilter != 'all') {
            $chartQuery = $chartQuery->where('status', $serviceFilter);
        }
        $chartData = $chartQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $months = [];
        $amounts = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $chartData->firstWhere('month', $i);
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
            $amounts[] = $monthData ? (float)$monthData->total_amount : 0;
        }

        // -------- JS Transactions --------
        $totalTransactionsJSbeforeCondition = Transaction::where('account_id', $accId)->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid']);
        if ($serviceFilter != 'all') {
            $totalTransactionsJSbeforeCondition = $totalTransactionsJSbeforeCondition->where('status', $serviceFilter);
        }
        $totalTransactionsJS = $totalTransactionsJSbeforeCondition->get();

        if ($serviceFilter == 'all') {
            $settledAmount = AmountSettlement::where('accountId', $accId)->sum('amount');
            $settleAmountCommission = AmountSettlement::where('accountId', $accId)->sum('commission');
        } else {
            $settledAmount = AmountSettlement::where('accountId', $accId)->where('payment_service', $serviceFilter)->sum('amount');
            $settleAmountCommission = AmountSettlement::where('accountId', $accId)->where('payment_service', $serviceFilter)->sum('commission');
        }

        return view('client.dashboard', compact(
            'accId',
            'gbpTotal',
            'usdTotal',
            'usdtTotal',
            'ethTotal',
            'eurTotal',
            'cadTotal',
            'inrTotal',
            'chartCurrency',
            'months',
            'amounts',
            'totalTransactions',
            'totalTransactionsJS',
            'settledAmount',
            'settleAmountCommission'
        ));
    }

    // public function getUpdatedAmountValue(Request $request)
    // {
    //     $period = $request->input('period');
    //     $currency = $request->input('currency');
    //     $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
    //     $pluck = "amount";
    //     $coloumn = "currency";
    //     // if ($currency == "USDC") {
    //     //     $pluck = "amount";
    //     //     $coloumn = "currency";
    //     // }
    //     // $pluck ="amount"; $coloumn = "currency";

    //     if ($period === 'total') {
    //         $curTrans = Transaction::where('account_id', $accId)
    //             ->where($coloumn, $currency)
    //             ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
    //             ->pluck($pluck);
    //         $amount = $curTrans->sum();
    //     } elseif ($period === 'thisMonth') {
    //         $currentYear = Carbon::now()->year;
    //         $currentMonth = Carbon::now()->month;

    //         $curTrans = Transaction::where('account_id', $accId)
    //             ->where($coloumn, $currency)
    //             ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
    //             ->whereYear('created_at', $currentYear)
    //             ->whereMonth('created_at', $currentMonth)
    //             ->pluck($pluck);

    //         $amount = $curTrans->sum();
    //     } elseif ($period === 'lastMonth') {
    //         $lastMonth = Carbon::now()->subMonth();  // This gives you the previous month
    //         $lastMonthYear = $lastMonth->year;
    //         $lastMonthMonth = $lastMonth->month;

    //         $curTrans = Transaction::where('account_id', $accId)
    //             ->where($coloumn, $currency)
    //             ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
    //             ->whereYear('created_at', $lastMonthYear)
    //             ->whereMonth('created_at', $lastMonthMonth)
    //             ->pluck($pluck);

    //         $amount = $curTrans->sum();
    //     } elseif ($period === 'lastFewMonths') {
    //         $threeMonthsAgo = Carbon::now()->subMonths(3);

    //         $curTrans = Transaction::where('account_id', $accId)
    //             ->where($coloumn, $currency)
    //             ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
    //             ->whereBetween('created_at', [$threeMonthsAgo, Carbon::now()])
    //             ->pluck($pluck);

    //         $amount = $curTrans->sum();
    //     } else {
    //         $amount = 0;
    //     }

    //     return response()->json([
    //         'amount' => round($amount, 5),
    //         'currency' => $currency,
    //     ]);
    // }

    public function getUpdatedChartData($currency)
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $query = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
            ->whereYear('created_at', date("Y"))
            ->where('currency', $currency)
            ->where('account_id', $accId)
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured', 'Paid']);

        $serviceFilter = session('service', 'all');

        if ($serviceFilter != 'all') {
            $query = $query->where('status', $serviceFilter);   // CORRECT COLUMN NAME
        }

        $data = $query
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $months = [];
        $amounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $data->firstWhere('month', $i);
            $months[] = date('M', mktime(0, 0, 0, $i, 1));  // Get the short month name
            $amounts[] = $monthData ? (float) $monthData->total_amount : 0;  // If no data, set as 0
        }

        return response()->json([
            'months' => $months,
            'amounts' => $amounts,
            'currency' => $currency
        ]);
    }

    public function downloadP3H2HDoc()
    {
        $filepath = public_path('docs/P3_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P3 Host-2-Host Documentation.docx');
    }

    public function downloadP3PayByLinkDoc()
    {
        $filepath = public_path('docs/API_Doc_P3_PayByLink_RyzenPay.docx');
        return response()->download($filepath, 'Pay-By-Link(X)_Doc.docx');
    }

    public function downloadP4H2HDoc()
    {
        $filepath = public_path('docs/P4_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P4 Host-2-Host Documentation.docx');
    }

    public function downloadP7H2HDoc()
    {
        $filepath = public_path('docs/P7_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P7 Host-2-Host Documentation.docx');
    }

    public function downloadP8H2HDoc()
    {
        $filepath = public_path('docs/P8_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P8 Host-2-Host Documentation.docx');
    }
    public function downloadP11H2HDoc()
    {
        $filepath = public_path('docs/P11_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P11 Host-2-Host Documentation.docx');
    }

    public function downloadP12H2HDoc()
    {
        $filepath = public_path('docs/P12_HOST2HOST_API_DOC.docx');

        if (!file_exists($filepath)) {
            abort(404, 'File not found');
        }

        return response()->download(
            $filepath,
            'P12 Host-2-Host Documentation.docx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ]
        );
    }

    public function downloadP13H2HDoc()
    {
        $filepath = public_path('docs/P13_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P13 Host-2-Host Documentation.docx');
    }

    public function developerArea()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;

        // Retrieve payment details for each payment method (P1, P2, P3, P4)
        $p1detail = POnePaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p2detail = PTwoPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p3detail = PThreePaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p4detail = PFourPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p5detail = PFivePaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p6detail = PSixPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p7detail = PSevenPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p8detail = PEightPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p9detail = PNinePaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p10detail = PTenPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p11detail = PaytoroPayment::where('accountId', $accId)->where('status', '=', '1')->first();
        $p12detail = PTwelvePaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p13detail = PThirteenPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p14detail = NiobiPayment::where('accountId', $accId)->where('status', '=', '1')->first();
        $p15detail = SmilePay::where('accountId', $accId)->where('status', '=', '1')->first();
        $p16detail = TrustitBanking::where('accountId', $accId)->where('status', '=', '1')->first();
        $p17detail = Direpay::where('accountId', $accId)->where('status', '=', '1')->first();
        $p18detail = PEighteenPaymentMethod::where('accountId', $accId)->where('status', '=', '1')->first();
        $p19detail = ValensPay::where('accountId', $accId)->where('status', '=', '1')->first();
        $p20detail = YaspaBanking::where('accountId', $accId)->where('status', '=', '1')->first();
        $p21detail = Alikassa::where('accountId', $accId)->where('status', '=', '1')->first();
        $p22detail = UniqoPay::where('accountId', $accId)->where('status', '=', '1')->first();
        $p23detail = UPIPayment::where('accountId', $accId)->where('status', '=', '1')->first();
        return view('developersArea.index', compact(
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
            'p23detail',
            'accId'
        ));
    }
}
