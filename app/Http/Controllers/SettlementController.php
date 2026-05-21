<?php

namespace App\Http\Controllers;

use App\Models\AmountSettlement;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettlementController extends Controller
{
    public function showSettlements()
    {
        $settlements = AmountSettlement::orderBy('created_at', 'desc')->paginate(300);
        $companies = Company::orderBy('company_name', 'asc')->where('status', '1')->get();

        return view('admin.settlements', compact('settlements', 'companies'));
    }

    public function addSettlement(Request $request)
    {
        $request->validate([
            'accountId' => 'string|required',
            'amount' => 'required',
            'commission' => 'nullable',
            'currency' => 'string|required',
            'description' => 'string|required',
            'payment_service' => 'string|required',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $settlement = new AmountSettlement();

        $settlement->accountId = $request->accountId;
        $settlement->amount = $request->amount;
        $settlement->commission = $request->commission;
        $settlement->currency = strtoupper($request->currency);
        $settlement->description = $request->description;
        $settlement->payment_service = $request->payment_service;
        $settlement->checkout_id = $uuid;
        $settlement->status = '1';

        $settlement->save();

        $amount = (float) $request->amount;
        $commission = (float) ($request->commission ?? 0);
        $settledAmount = number_format($amount + $commission, 8, '.', '');

        $transaction = new Transaction();

        $transaction->account_id = $request->accountId;
        $transaction->currency = strtoupper($request->currency);
        $transaction->amount = -$settledAmount;
        $transaction->description = $request->description;
        $transaction->payment_status = 'Completed';
        $transaction->checkout_id = $uuid;
        $transaction->payment_id = $uuid;
        $transaction->status = $request->payment_service;

        $transaction->save();

        return back()->with("success", "Amount Settlemt Added");
    }
}
