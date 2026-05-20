<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        //  if ($request->has('service')) {
        //     if ($request->service === 'all') {
        //         session()->forget('service');
        //     } else {
        //         session(['service' => $request->service]);
        //     }
        //     return back();   // reload page
        // }

        $serviceFilter = session('service','all');
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;

        $baseQuery = Transaction::where('account_id', $accId);
        if ($serviceFilter != 'all') {
            $baseQuery = $baseQuery->where('status', $serviceFilter);
        }

        $gbpTotal = (clone $baseQuery)->where('currency', 'GBP')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        $usdTotal = (clone $baseQuery)->where('currency', 'USD')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        $usdtTotal = (clone $baseQuery)->where('currency', 'USDT')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        $ethTotal = (clone $baseQuery)->where('currency', 'ETH')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        $eurTotal = (clone $baseQuery)->where('currency', 'EUR')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        $cadTotal = (clone $baseQuery)->where('currency', 'CAD')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        $inrTotal = (clone $baseQuery)->where('currency', 'INR')
            ->whereIn('payment_status', ['Approved','Completed','Complete','Succeeded','Success','Captured','Paid'])
            ->sum('amount');

        // -------- CHART DATA --------
        $currency = $request->input('chartCurrency') ?? 'USD';

        $chartQuery = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
            ->whereYear('created_at', date("Y"))
            ->where('currency', $currency)
            ->where('account_id', $accId)
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success', 'Captured','Paid']);

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

        // -------- TABLE TRANSACTIONS --------
        $totalTransactions = $baseQuery->get();

        // -------- JS Transactions --------
        $totalTransactionsJSbeforeCondition = Transaction::where('account_id', $accId)->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success','Captured','Paid']);
        if ($serviceFilter != 'all') {
           $totalTransactionsJSbeforeCondition = $totalTransactionsJSbeforeCondition->where('status', $serviceFilter);
        }
        $totalTransactionsJS = $totalTransactionsJSbeforeCondition->get();

        return response()->json([
            'success'               => true,
            'accId'                 => $accId,
            'gbpTotal'              => $gbpTotal,
            'usdTotal'              => $usdTotal,
            'usdtTotal'             => $usdtTotal,
            'ethTotal'              => $ethTotal,
            'eurTotal'              => $eurTotal,
            'cadTotal'              => $cadTotal,
            'inrTotal'              => $inrTotal,
            'months'                => $months,
            'amounts'               => $amounts,
            'totalTransactions'     => $totalTransactions,
            'totalTransactionsJS'   => $totalTransactionsJS,
        ],200);
    }

    public function dashboardTransactionsData(Request $request)
    {
        $user   = Auth::user();
        $accId  = Company::where('user_id', $user->id)->value('accountId');
        $serviceFilter = $request->input('serviceFilter', 'all');

        $baseQuery = Transaction::where('account_id', $accId);

        if ($serviceFilter !== 'all') {
            $baseQuery->where('status', $serviceFilter);
        }

        // Latest 5 transactions
        $transactions = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($t) {
                $t->blockchainTrxnHash = $t->transvoucher_blockchainHashTrxn;
                unset($t->transvoucher_blockchainHashTrxn);
                return $t;
            })
            ->makeHidden([
                'token',
                'id',
                'transvoucher_card_brand',
                'card_number',
            ]);

        // Status counts
        $statusQuery = clone $baseQuery;

        $totalTransactions = $statusQuery->count();

        $capturedTransactions = (clone $statusQuery)
            ->whereIn('payment_status', [ "Allocated", "Succeeded", "Completed", "Complete", "Approved", "Captured", "Success", "Overcharge", "Undercharge","Accepted","Paid"])->count();

        $awaitingTransactions = (clone $statusQuery)
            ->whereIn('payment_status', ['Pending', 'Waiting', 'Initiated', 'Redirect','Attempting', 'Created', 'Processing','PendingFork'])->count();

        $failedTransactions = (clone $statusQuery)
            ->whereIn('payment_status', ["Underchargeexpired", 'Failed', 'Declined', 'Expired', 'Cancelled', 'Error','Rejected'])->count();

        return response()->json([
            'success'            => true,
            'company_name'       => $user->name,
            'account_id'         => $accId,
            'captured_count'     => $capturedTransactions,
            'awaiting_count'     => $awaitingTransactions,
            'failed_count'       => $failedTransactions,
            'total_transactions' => $totalTransactions,
            'service_Filter'     => $serviceFilter,
            'transactions'       => $transactions,
        ]);
    }

    public function dashboardChartData(Request $request)
    {
        $user   = Auth::user();
        $accId  = Company::where('user_id', $user->id)->value('accountId');

        $currency      = $request->input('currency', 'USD');
        $serviceFilter = $request->input('serviceFilter', 'all');
        $currentYear   = date('Y');

        $query = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
            ->whereYear('created_at', $currentYear)
            ->where('currency', $currency)
            ->where('account_id', $accId)
            ->whereIn('payment_status', [ "Allocated", "Succeeded", "Completed", "Complete", "Approved", "Captured", "Success", "Overcharge", "Undercharge", "Paid"]);

        if ($serviceFilter !== 'all') {
            $query->where('status', $serviceFilter);
        }

        $monthlyData = $query
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyData->firstWhere('month', $i);
            $monthName = date('M', mktime(0, 0, 0, $i, 1));
            $chartData[$monthName] = $monthData ? (float) $monthData->total_amount : 0;
        }

        return response()->json([
            'success'        => true,
            'currency'       => $currency,
            'service_Filter' => $serviceFilter,
            'data'           => $chartData,
        ]);
    }

    public function dashboardCurrencyTotals(Request $request)
    {
        $serviceFilter = $request->input('serviceFilter','all');
        $validStatuses = ["Allocated", "Succeeded", "Completed", "Approved", "Captured", "Success", "Overcharge", "Undercharge" ];

        $startThisMonth = Carbon::now()->startOfMonth();
        $startLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endLastMonth   = Carbon::now()->subMonth()->endOfMonth();
        $start3Months   = Carbon::now()->subMonths(3)->startOfMonth();

        $rows = DB::table('transactions')
            ->selectRaw("
                currency,

                SUM(amount) as total,

                SUM(CASE WHEN created_at >= ? THEN amount ELSE 0 END) as thisMonth,

                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN amount ELSE 0 END) as lastMonth,

                SUM(CASE WHEN created_at >= ? THEN amount ELSE 0 END) as last3months
            ", [
                $startThisMonth,
                $startLastMonth,
                $endLastMonth,
                $start3Months
            ])
            ->whereIn('payment_status', $validStatuses)
             //Apply only when needed
            ->when($serviceFilter !== 'all', function ($q) use ($serviceFilter) {
                $q->where('status', $serviceFilter);
            })
            ->groupBy('currency')
            ->get()
            ->keyBy('currency');

        $currencies = ["USD","CAD","GBP","EUR","USDT","ETH","USDC","INR","KES"];
        $response = [];

        foreach ($currencies as $cur) {
            $data = $rows[$cur] ?? null;
            $response[$cur] = [
                "total"        => $data->total        ?? 0,
                "thisMonth"    => $data->thisMonth    ?? 0,
                "lastMonth"    => $data->lastMonth    ?? 0,
                "last3months"  => $data->last3months  ?? 0,
            ];
        }

        return response()->json([
            "success" => true,
            "currencies" => [$response]
        ], 200);

    }
}
