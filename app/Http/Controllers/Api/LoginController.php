<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PFourPaymentMethod;
use App\Models\POnePaymentMethod;
use App\Models\PThreePaymentMethod;
use App\Models\PTwoPaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->status == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $this->respondWithToken($token);

        return response()->json([
            'status' => true,
            'token' => $token->getData()->access_token,
            'expire_in' => $token->getData()->expires_in,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function home()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;

        // Retrieve transaction totals for each currency
        $gbpTotal = Transaction::where('account_id', $accId)
            ->where('currency', 'GBP')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
            ->sum('amount');
        $usdTotal = Transaction::where('account_id', $accId)
            ->where('currency', 'USD')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
            ->sum('amount');
        $usdtTotal = Transaction::where('account_id', $accId)
            ->where('currency', 'USDT')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
            ->sum('amount');
        $usdcTotal = Transaction::where('account_id', $accId)
            ->where('currency', 'USDC')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
            ->sum('amount');
        $eurTotal = Transaction::where('account_id', $accId)
            ->where('from_currency', 'EUR')
            ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
            ->sum('net_amount');


        // Retrieve payment details for each payment method (P1, P2, P3, P4)
        $p1detail = POnePaymentMethod::where('accountId', $accId)->first();
        if (!empty($p1detail->b_token)) {
            $payByLink_PS = [
                'redirect_url' => $p1detail->redirect_url . '/RyzenPay/p1/checkout-id/{checkout_id}',
                'b_token' => $p1detail->b_token,
            ];
        }

        $p2detail = PTwoPaymentMethod::where('accountId', $accId)->first();
        if (!empty($p2detail->b_token)) {
            $payByLink_Payb = [
                'redirect_url' => $p2detail->redirect_url . '/RyzenPay/checkout-id/p2{checkout_id}',
                'b_token' => $p2detail->b_token,
            ];
        }

        $p3detail = PThreePaymentMethod::where('accountId', $accId)->first();
        if (!empty($p3detail->b_token)) {
            $downloadUrl = asset('docs/API_Doc_P3_PayByLink_RyzenPay.docx');
            $h2h_downloadUrl = asset('docs/P3_HOST2HOST_API_DOC.docx');

            $payByLink_X = [
                'notification_url' => $p3detail->redirect_url . '/api/RyzenPay/p3/{checkout_id}',
                'b_token' => $p3detail->b_token,
                'download_doc' => $downloadUrl,
                'note' => 'Currency will be set to "EUR" only'
            ];

            $host2host_X = [
                'notification_url' => $p3detail->redirect_url . '/api/RyzenPay/p3/{checkout_id}',
                'b_token' => $p3detail->b_token,
                'download_doc' => $h2h_downloadUrl,
                'note' => 'Currency will be set to "EUR" only'
            ];
        }

        $p4detail = PFourPaymentMethod::where('accountId', $accId)->first();
        if (!empty($p4detail->b_token)) {
            $payByLink_STR = [
                'notification_url' => $p4detail->redirect_url . '/api/RyzenPay/p4/{checkout_id}',
                'b_token' => $p4detail->b_token
            ];
        }

        $credentials = [
            'Pay-By-Link (PS)' => $payByLink_PS ?? [],
            'Pay-By-Link (Payb)' => $payByLink_Payb ?? [],
            'Pay-By-Link (X)' => $payByLink_X ?? [],
            'Host-to-Host (X)' => $host2host_X ?? [],
            'Pay-By-Link (STR)' => $payByLink_STR ?? [],
        ];

        $data = [
            'total_GBP' => $gbpTotal,
            'total_USD' => $usdTotal,
            'total_USDT' => $usdtTotal,
            'total_USDC' => $usdcTotal,
            'total_EUR' => $eurTotal,
            'company_name' => Company::where('user_id', Auth::user()->id)->first()->company_name,
            'account_id' => $accId,
            'credentials' => $credentials,
        ];

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function filterHomeCurrency(Request $request)
    {
        $period = $request->input('period');
        $currency = strtoupper($request->input('currency'));
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $pluck = "net_amount";
        $coloumn = "from_currency";

        if ($currency == "USDC") {
            $pluck = "amount";
            $coloumn = "currency";
        }

        if ($period === 'total') {
            $curTrans = Transaction::where('account_id', $accId)
                ->where($coloumn, $currency)
                ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
                ->pluck($pluck);
            $amount = $curTrans->sum();
        } elseif ($period === 'thisMonth') {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $curTrans = Transaction::where('account_id', $accId)
                ->where($coloumn, $currency)
                ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->pluck($pluck);

            $amount = $curTrans->sum();
        } elseif ($period === 'lastMonth') {
            $lastMonth = Carbon::now()->subMonth();
            $lastMonthYear = $lastMonth->year;
            $lastMonthMonth = $lastMonth->month;

            $curTrans = Transaction::where('account_id', $accId)
                ->where($coloumn, $currency)
                ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
                ->whereYear('created_at', $lastMonthYear)
                ->whereMonth('created_at', $lastMonthMonth)
                ->pluck($pluck);

            $amount = $curTrans->sum();
        } elseif ($period === 'last3Months') {
            $threeMonthsAgo = Carbon::now()->subMonths(3);

            $curTrans = Transaction::where('account_id', $accId)
                ->where($coloumn, $currency)
                ->whereIn('payment_status', ['Approved', 'Completed', 'Complete', 'Succeeded', 'Success'])
                ->whereBetween('created_at', [$threeMonthsAgo, Carbon::now()])
                ->pluck($pluck);

            $amount = $curTrans->sum();
        } else {
            $amount = 0;
        }

        return response()->json([
            'status' => true,
            'data' => [
                'currency' => $currency,
                'amount' => $amount,
            ]
        ]);
    }


    public function transactions()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $transactions = Transaction::where('account_id', $accId)
            ->whereNotIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $transactions
        ]);
    }

    public function filterTransactions(Request $request)
    {
        $filter = $request->filter;
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;

        if ($filter === 'thisMonth') {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $transactions = Transaction::where('account_id', $accId)
                ->whereNotIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($filter === 'lastMonth') {
            $lastMonth = Carbon::now()->subMonth();
            $lastMonthYear = $lastMonth->year;
            $lastMonthMonth = $lastMonth->month;

            $transactions = Transaction::where('account_id', $accId)
                ->whereNotIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
                ->whereYear('created_at', $lastMonthYear)
                ->whereMonth('created_at', $lastMonthMonth)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($filter === 'last3Months') {
            $threeMonthsAgo = Carbon::now()->subMonths(3);

            $transactions = Transaction::where('account_id', $accId)
                ->whereNotIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
                ->whereBetween('created_at', [$threeMonthsAgo, Carbon::now()])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json([
            'status' => true,
            'data' => $transactions
        ]);
    }

    public function failedTransactions()
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $transactions = Transaction::where('account_id', $accId)
            ->whereIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $transactions
        ]);
    }

    public function filterFailedTransactions(Request $request)
    {
        $accId = Company::where('user_id', Auth::user()->id)->first()->accountId;
        $filter = $request->filter;

        if ($filter === 'thisMonth') {
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            $transactions = Transaction::where('account_id', $accId)
                ->whereIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($filter === 'lastMonth') {
            $lastMonth = Carbon::now()->subMonth();
            $lastMonthYear = $lastMonth->year;
            $lastMonthMonth = $lastMonth->month;

            $transactions = Transaction::where('account_id', $accId)
                ->whereIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
                ->whereYear('created_at', $lastMonthYear)
                ->whereMonth('created_at', $lastMonthMonth)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($filter === 'last3Months') {
            $threeMonthsAgo = Carbon::now()->subMonths(3);

            $transactions = Transaction::where('account_id', $accId)
                ->whereIn('payment_status', ['Declined', 'Rejected', 'Cancelled', 'Failed', 'Canceled', 'Payment-error'])
                ->whereBetween('created_at', [$threeMonthsAgo, Carbon::now()])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json([
            'status' => true,
            'data' => $transactions
        ]);
    }
}
