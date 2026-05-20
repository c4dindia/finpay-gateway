<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UniqoPay;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use App\Models\Transaction;

class UniqoPayController extends Controller
{
    protected $baseUrl = "https://api.uniqopay.com";

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = UniqoPay::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'amount'    => 'required|numeric|min:50',
            'currency' => 'required|in:INR',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $client = new Client();

        try {
            $response = $client->post($this->baseUrl . '/v1/payment/create-session', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $checkacc->merchant_api_key,
                ],
                'json' => [
                    'amount' => $validated['amount'] * 1000000, // Convert to micro-units
                    'currency' => $validated['currency'],
                    'user_id' => $uuid
                ],
                'verify' => false,
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 201) {
                $paymentLink = $data['data']['payment_url'];

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p22')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $validated['currency'];
                $trans->amount         = $validated['amount'];
                $trans->checkout_id    = $uuid;
                $trans->payment_id      = $data['data']['transaction_id'];
                $trans->payment_status = 'Pending';
                $trans->description     = 'Payment is being processed';
                $trans->status         = 'p22';
                $trans->token         = $data['data']['session_token'];

                $trans->save();

                Log::info("Transction Initialization Successful.");

                $responseData = [
                    "success"     => true,
                    "amount"      => $validated['amount'],
                    "currency"    => $validated['currency'],
                    "checkout_id" => $uuid,
                    "link"        => $paymentLink,
                ];

                return response()->json($responseData, 200);
            }
        } catch (ClientException $e) {
            Log::error("UniqoPay payment request failed: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create payment session link'], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        Log::info('Uniqo Pay webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('payment_id', $request->input('data.transaction_id'))->where('status', 'p22')->first();

        if (!$trans) {
            Log::warning("Transaction not found for ID: " . $request->input('data.transaction_id'));
            return;
        }

        $trans->currency       = $request->input('data.currency');
        $trans->payment_id     = $request->input('data.transaction_id');
        $trans->description     = 'Payment is completed';
        $trans->status         = 'p22';

        $status = $request->input('data.status');

        if (in_array($status, ['confirming', 'confirmed'])) {
            $trans->payment_status = 'Completed';
        } else {
            $trans->payment_status = ucfirst(strtolower($request->input('data.status')));
        }
        
        $trans->save();

        $account = UniqoPay::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/finpay/p22/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("p22 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("p22 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = UniqoPay::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p22')->first();
        if ($transaction == null) {
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'], 401);
        }

        return response()->json([
            'data' => [
                "currency" => $transaction->currency,
                "amount" => number_format($transaction->amount, 2),
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description,
                "created_at" => $transaction->created_at
            ]
        ], 200);
    }
}
