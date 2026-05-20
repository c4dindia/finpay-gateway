<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PSixPaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ClientTransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ClientTransactionsController extends Controller
{
    public function showTransactions(Request $request)
    {
        $accId = Company::where('user_id', Auth::id())->value('accountId');

        $p6Exist = PSixPaymentMethod::where('status', '1')->where('accountId', $accId)->exists();

        $select  = $request->name ?? 'total';
        $service = $request->service ?? 'all';
        $q       = trim((string) $request->query('q', ''));

        /*
        |--------------------------------------------------------------------------
        | Base Query (ONLY BUILD ONCE)
        |--------------------------------------------------------------------------
        */
        $query = Transaction::where('account_id', $accId)
            ->whereNotIn('payment_status', [ 'Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired','Error' ]);

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */
        if ($select === 'thisMonth') {
            $query->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);

        } elseif ($select === 'lastMonth') {
            $lastMonth = now()->subMonth();
            $query->whereYear('created_at', $lastMonth->year)
                  ->whereMonth('created_at', $lastMonth->month);

        } elseif ($select === 'lastFewMonths') {
            $query->whereBetween('created_at', [
                now()->subMonths(3),
                now()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Service Filter (status column)
        |--------------------------------------------------------------------------
        */
        if ($service !== 'all') {
            $query->where('status', $service);
        }

        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */
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

        return view('client.transactions', compact(
            'transactions',
            'select',
            'accId',
            'p6Exist',
            'service'
        ));
    }

    public function failedTransactions(Request $request)
    {
        $accId = Company::where('user_id', Auth::id())->value('accountId');

        $p6Exist = PSixPaymentMethod::where('status', '1')->where('accountId', $accId)->exists();

        $select  = $request->name ?? 'total';
        $service = $request->service ?? 'all';
        $q       = trim((string) $request->query('q', ''));

        /*
        |--------------------------------------------------------------------------
        | Base Query (ONLY BUILD ONCE)
        |--------------------------------------------------------------------------
        */
        $query = Transaction::where('account_id', $accId)
            ->whereIn('payment_status', [ 'Declined','Rejected','Cancelled','Failed', 'Canceled','Payment-error','Expired','Error' ]);

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */
        if ($select === 'thisMonth') {
            $query->whereYear('created_at', now()->year)
                  ->whereMonth('created_at', now()->month);

        } elseif ($select === 'lastMonth') {
            $lastMonth = now()->subMonth();
            $query->whereYear('created_at', $lastMonth->year)
                  ->whereMonth('created_at', $lastMonth->month);

        } elseif ($select === 'lastFewMonths') {
            $query->whereBetween('created_at', [
                now()->subMonths(3),
                now()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Service Filter (status column)
        |--------------------------------------------------------------------------
        */
        if ($service !== 'all') {
            $query->where('status', $service);
        }

        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        */
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

        return view('client.failed-transactions', compact('transactions','select'));
    }

    public function downloadTransactions(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $accId = Company::where('user_id', Auth::id())->value('accountId');

        if (!$accId) {
            return back()->withErrors([
                'account' => 'Account not found for this user.',
            ]);
        }

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();

        $transactions = Transaction::query()
            ->where('account_id', $accId)
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            // ->limit(300)
            ->get();

        // $pdf = Pdf::loadView('pdf.transactions-download', [
        //     'transactions' => $transactions,
        //     'start_date'   => $start,
        //     'end_date'     => $end,
        //     'company'      => Auth::user()->name,
        //     'email'        => Auth::user()->email,
        //     'generated_at' => now(),
        // ])->setPaper('a4', 'landscape');

        // return $pdf->download('transactions_' . now()->format('Ymd_His') . '.pdf');
        $fileName = 'transactions_' . now()->format('Ymd_His') . '.xlsx';
        $paymentStatus = null;

        return Excel::download(
            new ClientTransactionsExport($accId, $start, $end, $paymentStatus),
            $fileName
        );
    }
}
