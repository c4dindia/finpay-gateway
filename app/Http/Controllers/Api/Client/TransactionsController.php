<?php

namespace App\Http\Controllers\Api\Client;

use App\Exports\ClientTransactionsExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PSixPaymentMethod;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionsController extends Controller
{
    public function getTransactions(Request $request)
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;
        $p6Data = PSixPaymentMethod::where('status','1')->where('accountId',$accId)->first();
        if(!empty($p6Data)){
            $p6Exist = true;
        }else{
            $p6Exist = false;
        }

        $transactions = Transaction::where('account_id',$accId)
                                ->whereNotIn('payment_status',['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->orderBy('created_at','desc')
                                ->paginate(10);

        $select = 'total';

        if($request->name != null){

            if ($request->name === 'thisMonth') {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                $transactions = Transaction::where('account_id',$accId)
                                ->whereNotIn('payment_status',['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->whereYear('created_at', $currentYear)
                                ->whereMonth('created_at', $currentMonth)
                                ->orderBy('created_at','desc')
                                ->paginate(10);

                $select = 'thisMonth';

            } elseif ($request->name === 'lastMonth') {
                $lastMonth = Carbon::now()->subMonth();  // This gives you the previous month
                $lastMonthYear = $lastMonth->year;
                $lastMonthMonth = $lastMonth->month;

                $transactions = Transaction::where('account_id',$accId)
                                ->whereNotIn('payment_status',['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->whereYear('created_at', $lastMonthYear)
                                ->whereMonth('created_at', $lastMonthMonth)
                                ->orderBy('created_at','desc')
                                ->paginate(10);
                $select = 'lastMonth';

            } elseif ($request->name === 'lastFewMonths'){
                $threeMonthsAgo = Carbon::now()->subMonths(3);

                $transactions = Transaction::where('account_id',$accId)
                                ->whereNotIn('payment_status',['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->whereBetween('created_at', [$threeMonthsAgo, Carbon::now()])
                                ->orderBy('created_at','desc')
                                ->paginate(10);

                $select = 'lastFewMonths';

            }

            $q = trim((string) $request->query('q', ''));

            if ($q !== '') {
                $transactions = Transaction::where('account_id', $accId)
                    ->whereNotIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error', 'Expired', 'Error'])
                    ->where(function ($query) use ($q) {
                        $query->where('payment_id', 'like', "%{$q}%")
                            ->orWhere('checkout_id', 'like', "%{$q}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
                    ->appends(['q' => $q]);
            }

            // return view('client.transactions',compact('transactions','select', 'accId'));
            return response()->json([
                "success" => true,
                "accId"   => $accId,
                "name"  => $select,
                "p6service_enabled" => $p6Exist,
                "transactions" => $transactions
            ],200);
        }

        return response()->json([
                "success" => true,
                "accId"   => $accId,
                "p6service_enabled" => $p6Exist,
                "name"  => $select,
                "transactions" => $transactions
            ],200);
    }

    public function getFailedTransactions(Request $request)
    {
        $accId = Company::where('user_id',Auth::user()->id)->first()->accountId;

        $transactions = Transaction::where('account_id',$accId)
        ->whereIn('payment_status', ['Declined','Rejected','Cancelled','Failed','Canceled','Payment-error','Expired','Error'])
        ->orderBy('created_at','desc')
        ->paginate(10);

        $select = 'total';

        if($request->name != null){

            if ($request->name === 'thisMonth') {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;

                $transactions = Transaction::where('account_id',$accId)
                                ->whereIn('payment_status', ['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->whereYear('created_at', $currentYear)
                                ->whereMonth('created_at', $currentMonth)
                                ->orderBy('created_at','desc')
                                ->paginate(10);
                $select = 'thisMonth';

            } elseif ($request->name === 'lastMonth') {
                $lastMonth = Carbon::now()->subMonth();  // This gives you the previous month
                $lastMonthYear = $lastMonth->year;
                $lastMonthMonth = $lastMonth->month;

                $transactions = Transaction::where('account_id',$accId)
                                ->whereIn('payment_status', ['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->whereYear('created_at', $lastMonthYear)
                                ->whereMonth('created_at', $lastMonthMonth)
                                ->orderBy('created_at','desc')
                                ->paginate(10);

                $select = 'lastMonth';

            } elseif ($request->name === 'lastFewMonths'){
                $threeMonthsAgo = Carbon::now()->subMonths(3);

                $transactions = Transaction::where('account_id',$accId)
                                ->whereIn('payment_status', ['Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired', 'Error'])
                                ->whereBetween('created_at', [$threeMonthsAgo, Carbon::now()])
                                ->orderBy('created_at','desc')
                                ->paginate(10);

                $select = 'lastFewMonths';
            }

            $q = trim((string) $request->query('q', ''));

            if ($q !== '') {
                $transactions = Transaction::where('account_id', $accId)
                    ->whereIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error', 'Expired', 'Error'])
                    ->where(function ($query) use ($q) {
                        $query->where('payment_id', 'like', "%{$q}%")
                            ->orWhere('checkout_id', 'like', "%{$q}%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10)
                    ->appends(['q' => $q]);
            }

            return response()->json([
                "success" => true,
                "accId"   => $accId,
                "name"  => $select,
                "transactions" => $transactions
            ],200);
        }

        return response()->json([
                "success" => true,
                "accId"   => $accId,
                "name"  => $select,
                "transactions" => $transactions
            ],200);
    }

    public function getSpecificTransaction($checkout_id)
    {
        $trxn = Transaction::where('checkout_id',$checkout_id)->first();
        if($trxn == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        return response()->json([
            'data' =>  $trxn
        ],200);
    }

    public function downloadTransaction(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'payment_status'  => 'nullable|string',
        ]);

        $accId = Company::where('user_id', Auth::id())->value('accountId');

        if (!$accId) {
            return response()->json([
                "success" => false,
                'message' => 'Account not found for this user.',
            ], 404);
        }

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();

        $fileName = 'transactions_' . now()->format('Ymd_His') . '.xlsx';
        $paymentStatus = $validated['payment_status'] ?? null;

        return Excel::download(
            new ClientTransactionsExport($accId, $start, $end,$paymentStatus),
            $fileName
        );
    }
}
