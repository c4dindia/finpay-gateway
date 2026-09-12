<?php

namespace App\Http\Controllers;

use App\Models\PaynoraCustomer;
use App\Models\PaynoraPayment;
use App\Models\Transaction;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaynoraPaymentController extends Controller
{
    protected $baseUrl = "https://api.sandbox.paynora.net";
    protected $apiKey = "491aeb40-3e4f-458d-b57b-6e2726b85eef";
    protected $apiSecret = "osg4re274QsivdsIuJgCBAph9mepT9DinqQAZ5UkZIReElrots";

    // UPI_SEAMLESS hands back an encrypted redirectUrl that holds the UPI deep link.
    protected $paymentMethod = "UPI_SEAMLESS";

    public function createCustomer(Request $request, $accId)
    {
        $checkacc = PaynoraPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'firstName'   => 'required|string|max:60',
            'lastName'    => 'required|string|max:60',
            'email'       => 'required|email|max:60',
            'countryCode' => 'required|alpha|size:2',
            'birthDate'   => 'required|date_format:Y-m-d',
            'phone'       => 'required|string|max:15',
            'address1'    => 'required|string|max:100',
            'city'        => 'required|string|max:60',
            'state'       => 'required|string|max:60',
            'zipCode'     => 'required|string|max:12',
        ]);

        // Every merchant of ours sits on the same Paynora account, so the merchant
        // customer id is generated here to keep it unique across all of them.
        do {
            $merchantCustomerId = 'PYNC' . strtoupper(Str::random(20));
        } while (PaynoraCustomer::where('merchant_customer_id', $merchantCustomerId)->exists());

        $payload = [
            "firstName"          => $validated['firstName'],
            "lastName"           => $validated['lastName'],
            "customerEmail"      => $validated['email'],
            "countryCode"        => strtoupper($validated['countryCode']),
            // Documented as optional but Paynora rejects the request without it.
            "residenceCountry"   => strtoupper($validated['countryCode']),
            "birthDate"          => $validated['birthDate'],
            "merchantCustomerId" => $merchantCustomerId,
            "phoneNo"            => $validated['phone'],
            "address1"           => $validated['address1'],
            "city"               => $validated['city'],
            "state"              => $validated['state'],
            "zipCode"            => $validated['zipCode'],
        ];

        $data = $this->call('POST', '/customer/create', ['json' => $payload], 'Customer Create');

        $customerId = $data['data']['customerId'] ?? null;

        if (!$customerId) {
            return response()->json(['error' => 'Failed to create customer'], 500);
        }

        PaynoraCustomer::create([
            'company_id'           => $checkacc->company_id,
            'accountId'            => $checkacc->accountId,
            'provider_customer_id' => $customerId,
            'merchant_customer_id' => $merchantCustomerId,
            'first_name'           => $validated['firstName'],
            'last_name'            => $validated['lastName'],
            'customer_email'       => $validated['email'],
            'country_code'         => strtoupper($validated['countryCode']),
            'birth_date'           => $validated['birthDate'],
            'phone_no'             => $validated['phone'],
            'address1'             => $validated['address1'],
            'city'                 => $validated['city'],
            'state'                => $validated['state'],
            'zip_code'             => $validated['zipCode'],
            'status'               => '1',
        ]);

        Log::info("Paynora: Customer created for Account ID:- " . $accId);

        return response()->json([
            "success"     => true,
            "customer_id" => $customerId,
        ], 200);
    }

    public function getCustomers($accId)
    {
        $checkacc = PaynoraPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $customers = PaynoraCustomer::where('accountId', $checkacc->accountId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($customer) {
                return [
                    "customer_id" => $customer->provider_customer_id,
                    "firstName"   => $customer->first_name,
                    "lastName"    => $customer->last_name,
                    "email"       => $customer->customer_email,
                    "phone"       => $customer->phone_no,
                    "countryCode" => $customer->country_code,
                    "birthDate"   => $customer->birth_date,
                    "address1"    => $customer->address1,
                    "city"        => $customer->city,
                    "state"       => $customer->state,
                    "zipCode"     => $customer->zip_code,
                    "created_at"  => $customer->created_at,
                ];
            });

        return response()->json([
            "success"   => true,
            "total"     => $customers->count(),
            "customers" => $customers,
        ], 200);
    }

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = PaynoraPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'customerId'  => 'required|string',
            'amount'      => 'required|numeric|min:1|regex:/^\d+(\.\d{1,2})?$/',
            'currency'    => 'required|alpha|size:3',
            'notes'       => 'required|string|max:100',
            'merchantRef' => 'required|regex:/^[A-Za-z0-9_-]+$/|max:36',
            'clientIp'    => 'required|ip',
        ]);

        // A customerId from another merchant must not be usable here.
        $customer = PaynoraCustomer::where('provider_customer_id', $validated['customerId'])
            ->where('accountId', $checkacc->accountId)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Unknown customerId for this account'], 422);
        }

        // Every merchant of ours shares one Paynora account, so the reference has to
        // be unique across all of them, not just within this account.
        $duplicate = Transaction::where('status', 'p25')
            ->where('customer_details', $validated['merchantRef'])
            ->exists();

        if ($duplicate) {
            return response()->json(['error' => 'merchantRef already used'], 409);
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $merchantRef = $validated['merchantRef'];

        $payload = [
            "amount"        => (float) $validated['amount'],
            "currency"      => strtoupper($validated['currency']),
            "merchantRef"   => $merchantRef,
            "notes"         => $validated['notes'],
            "paymentMethod" => $this->paymentMethod,
            "callbackUrl"   => config('services.p25.callback_url'),
            "clientIp"      => $validated['clientIp'],
            "customerId"    => $validated['customerId'],
        ];

        $data = $this->call('POST', '/api/payment', ['json' => $payload], 'Checkout');

        $paymentId = $data['data']['paymentId'] ?? null;
        $redirectUrl = $data['data']['redirectUrl'] ?? null;

        if (!$paymentId || !$redirectUrl) {
            return response()->json(['error' => 'Failed to create checkout'], 500);
        }

        $paymentData = $this->decryptRedirectUrl($redirectUrl);

        if (!$paymentData) {
            Log::error('Paynora: Unable to decrypt redirectUrl for Checkout ID:- ' . $uuid);
            return response()->json(['error' => 'Failed to create checkout'], 500);
        }

        // Live returns a upi:// deep link, the sandbox returns a simulator page URL.
        $type = Str::startsWith($paymentData, 'upi://') ? 'INTENT' : 'URL';

        $token = Crypt::encryptString(json_encode([
            'checkout_id' => $uuid,
            'type'        => $type,
            'data'        => $paymentData,
            'expires_at'  => now()->addMinutes(config('services.p25.payment_expiry_minutes'))->timestamp,
        ]));

        $payUrl = route('p25.payment.page', [
            'checkout_id' => $uuid,
        ]) . '?' . http_build_query([
            'token' => $token,
        ]);

        $trans = new Transaction();

        $trans->account_id       = $checkacc->accountId;
        $trans->currency         = strtoupper($validated['currency']);
        $trans->amount           = $validated['amount'];
        $trans->checkout_id      = $uuid;
        $trans->payment_id       = $paymentId;
        $trans->payment_status   = 'Pending';
        $trans->description      = $this->statusDescription('Pending');
        $trans->card_number      = $validated['customerId'];
        $trans->status           = 'p25';
        $trans->customer_details = $merchantRef;

        $trans->save();

        Log::info("Paynora: Payin Initialization with Checkout ID:- " . $uuid);

        return response()->json([
            "success"     => true,
            "amount"      => $validated['amount'],
            "currency"    => strtoupper($validated['currency']),
            "checkout_id" => $uuid,
            "link"        => $payUrl,
        ], 200);
    }

    public function paymentPage(Request $request, $checkout_id)
    {
        $payload = json_decode(Crypt::decryptString($request->token), true);

        if (($payload['checkout_id'] ?? null) !== $checkout_id) {
            abort(403, 'Invalid payment token.');
        }

        $type = $payload['type'];
        $paymentData = $payload['data'];

        $transaction = Transaction::where('checkout_id', $checkout_id)->where('status', 'p25')->first();

        if (!$transaction) {
            abort(403, 'Invalid payment token.');
        }

        $createdAt = $transaction->created_at->timestamp;
        $expiresAt = $createdAt + (config('services.p25.payment_expiry_minutes') * 60); // expiry minutes
        $isExpired = time() > $expiresAt;

        if ($isExpired && $transaction->payment_status == 'Pending') {
            $transaction->payment_status = 'Expired';
            $transaction->description = $this->statusDescription('Expired');
            $transaction->save();
        }

        // The sandbox hands back a hosted simulator page instead of a UPI deep link.
        if ($type === 'URL' && !$isExpired) {
            return redirect($paymentData);
        }

        return view('payment.upi-pyn.checkout', compact('checkout_id', 'type', 'transaction', 'isExpired', 'expiresAt', 'paymentData'));
    }

    public function getPayinStatus($checkout_id)
    {
        $trans = Transaction::where('checkout_id', $checkout_id)->where('status', 'p25')->first();

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

        $status = $this->fetchRemoteStatus($trans->payment_id);

        if ($status) {
            $this->applyStatus($trans, $status);

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
            ->where('status', 'p25')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false], 404);
        }

        if (!in_array($transaction->payment_status, ['Completed', 'Failed'])) {
            // A payment made in the last seconds can still land after our timer runs
            // out, so ask Paynora once more before writing the transaction off.
            $status = $this->fetchRemoteStatus($transaction->payment_id);

            if ($status) {
                $this->applyStatus($transaction, $status);
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

    public function handleNotification(Request $request)
    {
        // The Signature header is an HMAC-SHA256 of the raw body, so keep the
        // untouched payload instead of relying on the parsed request.
        $raw = $request->getContent();

        $payload = json_decode($raw, true);

        // The webhook nests the real data as an escaped JSON string in requestPayload.
        $data = null;
        if (is_array($payload) && !empty($payload['requestPayload'])) {
            $data = json_decode($payload['requestPayload'], true);
        }

        Log::info('Paynora: Webhook request', [
            'signature'    => $request->header('Signature'),
            'event_type'   => $payload['eventType'] ?? null,
            'webhook_type' => $payload['webhookType'] ?? null,
            'data'         => $data,
            'raw'          => mb_substr($raw, 0, 2000),
        ]);

        $signature = $request->header('Signature');
        $expected = hash_hmac('sha256', $raw, $this->apiSecret);

        if (!$signature || !hash_equals($expected, $signature)) {
            Log::warning('Paynora: Webhook signature mismatch');
            return response()->json(['status' => 'success'], 200);
        }

        // Refund and payout events are not handled by this service.
        if (($payload['webhookType'] ?? null) !== 'PAYMENT' || !is_array($data)) {
            return response()->json(['status' => 'success'], 200);
        }

        $trans = Transaction::where('status', 'p25')
            ->where('customer_details', $data['merchantRef'] ?? null)
            ->first();

        if (!$trans) {
            Log::warning("Paynora: Transaction not found for merchantRef:- " . ($data['merchantRef'] ?? ''));
            return response()->json(['status' => 'success'], 200);
        }

        $this->applyStatus($trans, $data['status'] ?? '', $data);

        $account = PaynoraPayment::where('accountId', $trans->account_id)->first();

        if ($account) {
            try {
                $headers = [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $account->b_token,
                ];

                $webhook = new Client();
                $resp = $webhook->get($account->redirect_url . '/api/finpay/p25/' . $trans->checkout_id, [
                    'headers' => $headers,
                    'timeout' => 15,
                ]);
                Log::info("P25 forward OK response from client, status: {$resp->getStatusCode()}");
            } catch (RequestException $e) {
                Log::warning("P25 forward api to client failed: " . $e->getMessage());
            }
        }

        // A non 200 response makes them mark the webhook as failed and retry it.
        return response()->json(['status' => 'success'], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = PaynoraPayment::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }

        $transaction = Transaction::where('checkout_id', $checkout_id)
            ->where('account_id', $accId)
            ->where('status', 'p25')
            ->first();

        if ($transaction == null) {
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'], 401);
        }

        return response()->json([
            'data' => [
                "currency"       => $transaction->currency,
                "amount"         => number_format($transaction->amount, 2),
                "checkout_id"    => $transaction->checkout_id,
                "merchant_ref"   => $transaction->customer_details,
                "payment_id"     => $transaction->payment_id,
                "utr"            => $transaction->token,
                "payment_status" => ucfirst($transaction->payment_status),
                "description"    => $transaction->description,
                "created_at"     => $transaction->created_at
            ]
        ], 200);
    }

    /**
     * Fetch the live transaction status for a paymentId.
     *
     * @param  string|null  $paymentId
     * @return string|null
     */
    protected function fetchRemoteStatus($paymentId)
    {
        if (!$paymentId) {
            return null;
        }

        $data = $this->call('GET', '/api/transaction/' . $paymentId, [], 'Transaction Status');

        return $data['data']['transactionStatus'] ?? null;
    }

    /**
     * Send a request to Paynora and return the decoded body on success.
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array  $options
     * @param  string  $label
     * @return array|null
     */
    protected function call($method, $path, array $options, $label)
    {
        $client = new Client();

        try {
            $response = $client->request($method, $this->baseUrl . $path, array_merge([
                'headers' => [
                    'X-API-KEY'    => $this->apiKey,
                    'X-API-SECRET' => $this->apiSecret,
                    'Accept'       => 'application/json',
                ],
                'timeout' => 30,
                'http_errors' => false,
            ], $options));

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            $statusCode = $response->getStatusCode();

            // Customer create answers 201, the rest answer 200.
            if ($statusCode >= 200 && $statusCode < 300 && ($data['errorMessage'] ?? null) === 'Success') {
                return $data;
            }

            Log::error('Paynora: ' . $label . ' Request Failed', [
                'http_status' => $statusCode,
                'body'        => mb_substr($body, 0, 1000),
            ]);

            return null;
        } catch (RequestException $e) {
            Log::error('Paynora: ' . $label . ' Failed:- ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            Log::error('Paynora: ' . $label . ' Failed:- ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Map a Paynora status onto the transaction and save it.
     *
     * @param  \App\Models\Transaction  $trans
     * @param  string  $status
     * @param  array  $data  Webhook payload, when the call came from a webhook.
     * @return void
     */
    protected function applyStatus($trans, $status, array $data = [])
    {
        $status = strtoupper(trim((string) $status));

        if ($status === 'COMPLETED') {
            $trans->payment_status = 'Completed';
        } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
            $trans->payment_status = 'Failed';
        } elseif ($status === 'EXPIRED') {
            $trans->payment_status = 'Expired';
        } elseif ($status === 'PENDING') {
            $trans->payment_status = 'Pending';
        } elseif ($status === 'REFUNDED' || $status === 'PARTIALLY_REFUNDED') {
            $trans->payment_status = 'Refunded';
        } else {
            $trans->payment_status = ucfirst(strtolower($status));
        }

        // utr is undocumented but does come on the webhook.
        if (!empty($data['utr'])) {
            $trans->token = $data['utr'];
        }

        $trans->description = $this->statusDescription($trans->payment_status);
        $trans->status = 'p25';

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
     * Decrypt the UPI_SEAMLESS redirectUrl.
     * AES-256-CBC with the API secret uppercased: first 32 chars key, first 16 chars IV.
     *
     * @param  string  $encoded
     * @return string|null
     */
    protected function decryptRedirectUrl($encoded)
    {
        $upper = strtoupper($this->apiSecret);

        if (strlen($upper) < 32) {
            Log::error('Paynora: API secret too short to build the key.');
            return null;
        }

        // Base64 "+" can arrive as a space depending on how it was transported.
        $encoded = str_replace(' ', '+', trim($encoded));

        $decrypted = openssl_decrypt(
            base64_decode($encoded),
            'AES-256-CBC',
            substr($upper, 0, 32),
            OPENSSL_RAW_DATA,
            substr($upper, 0, 16)
        );

        if ($decrypted === false) {
            Log::error('Paynora: redirectUrl decryption failed.');
            return null;
        }

        return trim($decrypted);
    }
}
