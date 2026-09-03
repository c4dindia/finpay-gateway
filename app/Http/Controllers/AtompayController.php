<?php

namespace App\Http\Controllers;

use App\Models\AtomPay;
use App\Models\Transaction;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AtompayController extends Controller
{
    protected $paymentInitUrl = "https://atompay.in/payx/paymentinit";
    protected $statusUrl = "https://atompay.in/payx/statusenquiry";

    // AtomPay only supports these fixed keywords for a UPI intent transaction.
    protected $mop = "UPI";
    protected $mopType = "UPI";
    protected $mopDetails = "I";

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = AtomPay::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'CustRefNum' => 'required|alpha_num|min:8|max:32',
            'Amount'     => 'required|numeric|min:1|regex:/^\d+(\.\d{1,2})?$/',
            'ContactNo'  => 'required|digits:10',
            'EmailId'    => 'required|email|min:10|max:60',
        ]);

        // Stray whitespace around the credentials breaks the AES key, so trim them here.
        $authId = trim((string) $checkacc->auth_id);
        $authKey = trim((string) $checkacc->auth_key);

        if (!$authId || !$authKey) {
            Log::error("UPI ATM: Auth ID / Auth Key not set for Account ID:- " . $accId);
            return response()->json(['error' => 'Something went wrong, please try again!'], 503);
        }

        // CustRefNum is the merchant's own tracking id, so it has to stay unique per account.
        $duplicate = Transaction::where('status', 'p24')
            ->where('account_id', $checkacc->accountId)
            ->where('customer_details', $validated['CustRefNum'])
            ->exists();

        if ($duplicate) {
            return response()->json(['error' => 'CustRefNum already used'], 409);
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $payload = [
            "AuthID"          => $authId,
            "AuthKey"         => $authKey,
            "CustRefNum"      => $validated['CustRefNum'],
            "txn_Amount"      => number_format((float) $validated['Amount'], 2, '.', ''),
            "PaymentDate"     => now()->format('Y-m-d H:i:s'),
            "ContactNo"       => $validated['ContactNo'],
            "EmailId"         => $validated['EmailId'],
            "IntegrationType" => "SEAMLESS",
            "CallbackURL"     => config('services.p24.callback_url'),
            "adf1"            => "NA",
            "adf2"            => "NA",
            "adf3"            => "NA",
            "MOP"             => $this->mop,
            "MOPType"         => $this->mopType,
            "MOPDetails"      => $this->mopDetails,
        ];

        $encData = $this->encryptData(json_encode($payload), $authKey);

        if (!$encData) {
            return response()->json(['error' => 'Something went wrong, please try again!'], 500);
        }

        $client = new Client();
        try {
            $response = $client->post($this->paymentInitUrl, [
                'query' => [
                    'encData' => $encData,
                    'AuthID'  => $authId,
                ],
                'timeout' => 30,
                'http_errors' => false,
                'allow_redirects' => ['track_redirects' => true],
            ]);

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            $statusCode = $response->getStatusCode();

            $respData = $this->decryptData($data['respData'] ?? null, $authKey);

            if ($statusCode !== 200 || !$respData) {
                Log::error('UPI ATM: Checkout Request Failed', [
                    'http_status'  => $statusCode,
                    'content_type' => $response->getHeaderLine('Content-Type'),
                    'redirects'    => $response->getHeaderLine('X-Guzzle-Redirect-History'),
                    'sent'         => array_merge($payload, ['AuthKey' => '***']),
                    'body'         => mb_substr($body, 0, 500),
                ]);
                return response()->json(['error' => 'Failed to create checkout'], 500);
            }

            $qrString = str_replace(' ', '', $respData['qrString'] ?? '');

            if (!$qrString) {
                Log::error('UPI ATM: Checkout Request Failed:- ' . json_encode($respData));
                return response()->json(['error' => 'Failed to create checkout'], 500);
            }

            $token = Crypt::encryptString(json_encode([
                'checkout_id' => $uuid,
                'type'        => 'INTENT',
                'mobile'      => $validated['ContactNo'],
                'email'       => $validated['EmailId'],
                'data'        => $qrString,
                'expires_at'  => now()->addMinutes(config('services.p24.payment_expiry_minutes'))->timestamp,
            ]));

            $payUrl = route('p24.payment.page', [
                'checkout_id' => $uuid,
            ]) . '?' . http_build_query([
                'token' => $token,
            ]);

            $trans = new Transaction();

            $trans->account_id       = $checkacc->accountId;
            $trans->currency         = 'INR';
            $trans->amount           = $validated['Amount'];
            $trans->checkout_id      = $uuid;
            $trans->payment_id       = $respData['AggRefNo'] ?? null;
            $trans->payment_status   = 'Pending';
            $trans->description      = $this->statusDescription('Pending');
            $trans->card_number      = $checkacc->auth_id;
            $trans->status           = 'p24';
            $trans->customer_details = $validated['CustRefNum'];

            $trans->save();

            Log::info("UPI ATM: Payin Initialization with Checkout ID:- " . $uuid);

            return response()->json([
                "success"     => true,
                "amount"      => $validated['Amount'],
                "currency"    => 'INR',
                "checkout_id" => $uuid,
                "link"        => $payUrl,
            ], 200);
        } catch (RequestException $e) {
            Log::error('UPI ATM: Checkout Creation Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create checkout'], 500);
        } catch (Exception $e) {
            Log::error('UPI ATM: Checkout Creation Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create checkout'], 500);
        }
    }

    public function paymentPage(Request $request, $checkout_id)
    {
        $payload = json_decode(Crypt::decryptString($request->token), true);

        if (($payload['checkout_id'] ?? null) !== $checkout_id) {
            abort(403, 'Invalid payment token.');
        }

        $type = $payload['type'];
        $paymentData = $payload['data'];

        $transaction = Transaction::where('checkout_id', $checkout_id)->where('status', 'p24')->first();

        if (!$transaction) {
            abort(403, 'Invalid payment token.');
        }

        $createdAt = $transaction->created_at->timestamp;
        $expiresAt = $createdAt + (config('services.p24.payment_expiry_minutes') * 60); // expiry minutes
        $isExpired = time() > $expiresAt;

        if ($isExpired && $transaction->payment_status == 'Pending') {
            $transaction->payment_status = 'Expired';
            $transaction->description = $this->statusDescription('Expired');
            $transaction->save();
        }

        return view('payment.upi-atm.checkout', compact('checkout_id', 'type', 'transaction', 'isExpired', 'expiresAt', 'paymentData'));
    }

    public function getPayinStatus($checkout_id)
    {
        $trans = Transaction::where('checkout_id', $checkout_id)->where('status', 'p24')->first();

        if (!$trans) {
            return response()->json(['success' => false], 404);
        }

        if ($trans->payment_status === 'Completed') {
            return response()->json([
                "success"     => true,
                "checkout_id" => $trans->checkout_id,
                "payment_id"  => $trans->payment_id,
                "status"      => $trans->payment_status,
            ]);
        }

        $account = AtomPay::where('accountId', $trans->account_id)->first();
        $data = $this->fetchRemoteStatus($account, $trans->customer_details);

        if ($data) {
            $this->applyStatus($trans, $data);

            return response()->json([
                "success"     => true,
                "checkout_id" => $trans->checkout_id,
                "payment_id"  => $trans->payment_id,
                "status"      => $trans->payment_status,
            ]);
        }

        return response()->json([
            "success"     => false,
            "checkout_id" => $trans->checkout_id,
            "payment_id"  => $trans->payment_id,
            "status"      => $trans->payment_status,
        ]);
    }

    public function markPayinExpired($checkout_id)
    {
        $transaction = Transaction::where('checkout_id', $checkout_id)
            ->where('status', 'p24')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false], 404);
        }

        if (!in_array($transaction->payment_status, ['Completed', 'Failed'])) {
            // The AtomPay deep link is only valid for 5 minutes, but a payment made in
            // the last seconds can still land after our timer runs out. Ask AtomPay one
            // final time before writing the transaction off as expired.
            $account = AtomPay::where('accountId', $transaction->account_id)->first();
            $data = $this->fetchRemoteStatus($account, $transaction->customer_details);

            if ($data) {
                $this->applyStatus($transaction, $data);
            }

            if (!in_array($transaction->payment_status, ['Completed', 'Failed'])) {
                $transaction->payment_status = 'Expired';
                $transaction->description = $this->statusDescription('Expired');
                $transaction->save();
            }
        }

        return response()->json([
            'success' => true,
            'status'  => $transaction->payment_status,
        ]);
    }

    /**
     * Query AtomPay's status enquiry API for one transaction.
     *
     * @param  \App\Models\AtomPay|null  $account
     * @param  string|null  $custRefNum
     * @return array|null
     */
    protected function fetchRemoteStatus($account, $custRefNum)
    {
        if (!$account || !$account->auth_id || !$custRefNum) {
            Log::warning("UPI ATM: Unable to query status, missing account or CustRefNum:- " . $custRefNum);
            return null;
        }

        $client = new Client();

        try {
            $response = $client->post($this->statusUrl, [
                'query' => [
                    'AuthID'     => trim((string) $account->auth_id),
                    'CustRefNum' => $custRefNum,
                ],
                'timeout' => 30,
                'http_errors' => false,
            ]);

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($response->getStatusCode() === 200 && isset($data['payStatus'])) {
                return $data;
            }

            Log::error('UPI ATM: Transaction Status Request Failed', [
                'http_status' => $response->getStatusCode(),
                'body'        => mb_substr($body, 0, 2000),
            ]);

            return null;
        } catch (RequestException $e) {
            Log::warning("UPI ATM: Transaction Status Update Failed: " . $e->getMessage());
            return null;
        } catch (Exception $e) {
            Log::warning("UPI ATM: Transaction Status Update Failed: " . $e->getMessage());
            return null;
        }
    }

    public function handleNotification(Request $request)
    {
        Log::info('UPI ATM: Payin webhook request', [
            'payload' => $request->all(),
        ]);

        $authId = $request->input('AuthID');

        $account = AtomPay::where('auth_id', $authId)->first();

        if (!$account) {
            Log::warning("UPI ATM: Account not found for Auth ID:- " . $authId);
            return response()->json(['success' => false], 200);
        }

        // AtomPay posts the final response encrypted in `respData`, but the plain
        // JSON body is handled too in case the callback is sent unencrypted.
        $data = $request->all();

        if ($request->filled('respData')) {
            $decrypted = $this->decryptData($request->input('respData'), trim((string) $account->auth_key));

            if (!$decrypted) {
                Log::warning("UPI ATM: Unable to decrypt webhook respData for Auth ID:- " . $authId);
                return response()->json(['success' => false], 200);
            }

            $data = $decrypted;
        }

        $custRefNum = $data['CustRefNum'] ?? null;

        $trans = Transaction::where('status', 'p24')
            ->where('account_id', $account->accountId)
            ->where('customer_details', $custRefNum)
            ->first();

        if (!$trans) {
            Log::warning("UPI ATM: Transaction not found for CustRefNum:- " . $custRefNum);
            return response()->json(['success' => false], 200);
        }

        $this->applyStatus($trans, $data);

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/finpay/p24/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("P24 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("P24 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = AtomPay::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }

        $transaction = Transaction::where('checkout_id', $checkout_id)
            ->where('account_id', $accId)
            ->where('status', 'p24')
            ->first();

        if ($transaction == null) {
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'], 401);
        }

        return response()->json([
            'data' => [
                "currency"       => $transaction->currency,
                "amount"         => number_format($transaction->amount, 2),
                "checkout_id"    => $transaction->checkout_id,
                "cust_ref_num"   => $transaction->customer_details,
                "payment_id"     => $transaction->payment_id,
                "utr"            => $transaction->token,
                "payment_status" => ucfirst($transaction->payment_status),
                "description"    => $transaction->description,
                "created_at"     => $transaction->created_at
            ]
        ], 200);
    }

    /**
     * Map an AtomPay response payload onto the transaction and save it.
     *
     * @param  \App\Models\Transaction  $trans
     * @param  array  $data
     * @return void
     */
    protected function applyStatus($trans, array $data)
    {
        $status = strtolower(trim($data['payStatus'] ?? ''));
        $respCode = strtoupper(trim($data['resp_code'] ?? ''));

        if ($status === 'ok') {
            $trans->payment_status = 'Completed';
        } elseif ($status === 'f') {
            $trans->payment_status = 'Failed';
        } elseif ($status === 'pppp') {
            $trans->payment_status = 'Pending';
        } elseif ($status === 'na' || $respCode === 'NA' || $status === '') {
            // AtomPay stops returning timed out transactions after 20 minutes,
            // those have to be treated as failed.
            $trans->payment_status = 'Failed';
        } else {
            $trans->payment_status = ucfirst($status);
        }

        if (!empty($data['AggRefNo'])) {
            $trans->payment_id = $data['AggRefNo'];
        }

        if (!empty($data['serviceRRN']) && strtoupper($data['serviceRRN']) !== 'NA') {
            $trans->token = $data['serviceRRN'];
        }

        // The payer VPA comes back in adf3 on the final response.
        if (!empty($data['adf3']) && strtoupper($data['adf3']) !== 'NA') {
            $trans->payer_details = ['vpa' => $data['adf3']];
        }

        $trans->description = $this->statusDescription($trans->payment_status);
        $trans->status = 'p24';

        $trans->save();
    }

    /**
     * Human readable description kept in sync with the payment status.
     *
     * @param  string  $status
     * @return string
     */
    protected function statusDescription($status)
    {
        return 'Your transaction is ' . strtolower($status) . '.';
    }

    /**
     * AES-256-CBC encrypt with the merchant AuthKey (IV = first 16 chars of the key).
     *
     * @param  string  $plain
     * @param  string  $authKey
     * @return string|null
     */
    protected function encryptData($plain, $authKey)
    {
        if (strlen($authKey) < 16) {
            Log::error('UPI ATM: Auth Key too short to build the IV.');
            return null;
        }

        $encrypted = openssl_encrypt($plain, 'AES-256-CBC', $authKey, OPENSSL_RAW_DATA, substr($authKey, 0, 16));

        if ($encrypted === false) {
            Log::error('UPI ATM: Request encryption failed.');
            return null;
        }

        return base64_encode($encrypted);
    }

    /**
     * Decrypt an AtomPay respData blob back into an array.
     *
     * @param  string|null  $encoded
     * @param  string  $authKey
     * @return array|null
     */
    protected function decryptData($encoded, $authKey)
    {
        if (!$encoded || strlen($authKey) < 16) {
            return null;
        }

        // AtomPay posts the webhook form encoded without escaping the base64 "+"
        // characters, so they reach us as spaces and have to be put back.
        $encoded = str_replace(' ', '+', trim($encoded));

        $decrypted = openssl_decrypt(base64_decode($encoded), 'AES-256-CBC', $authKey, OPENSSL_RAW_DATA, substr($authKey, 0, 16));

        if ($decrypted === false) {
            Log::error('UPI ATM: Response decryption failed.');
            return null;
        }

        $data = json_decode($decrypted, true);

        return is_array($data) ? $data : null;
    }
}
