<?php

namespace App\Http\Controllers;

use App\Models\UPIPayment;
use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use App\Models\Transaction;
use App\Models\UpiMerchant;
use Illuminate\Support\Facades\Crypt;

class UpiPaymentController extends Controller
{
    protected $baseUrl = "https://gatewayeng.azure-api.net/upi/api";
    protected $subscriptionKey = "1ceb19d850404bac9ae417b1ba0a4191";
    protected $bpmidentifier = "bpm0003";
    protected $checkSum = "92992";
    protected $channelId = "TG2";
    protected $authToken = "eyJhbGciOiJIUzUxMiJ9.eyJqdGkiOiI4MTIiLCJzdWIiOiJhdXRoIiwiaXNzIjoiVFJBTlNYVCIsIlNFU1NJT05JRCI6IjAiLCJTRUNSRVQiOiIiLCJQUk9ETElTVCI6W10sIlVTRVJJRCI6IjAiLCJQT1JUQUwiOiIiLCJFTlYiOiJwcm9kIn0.5oOCcEOpIc7J-KIwWdW21jQ77aOxX7iUwe4y7EhE69YL0oKgS-B-UWBaHyIZhNqRJS93_GvGGyuGTurLE60fNg";
    protected $merchantName = "Adambil Enterprise Private Limited";
    protected $userName = "API0439";
    protected $password = "urLitaHFjHdpI8zU32KQYw==";

    protected $payoutUrl = "https://gatewayeng.azure-api.net/payout/upi/api";
    protected $clientId = "3c083382-f3e6-435b-b80e-3ce71be7645e";
    protected $clientSecret = "94ebaBbH9hKFzgx6BK8w4q63AzdNYUqa";

    public function accessToken()
    {
        $client = new Client();
        try {
            $response = $client->post($this->baseUrl . "/1.0/auth", [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Authorization' => 'Bearer ' . $this->authToken,
                ],
                'json' => [
                    "payload" => [
                        "userName" => $this->userName,
                        "password" => $this->password
                    ],
                    "checksum" => $this->checkSum
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                return $data['token'];
            } else {
                Log::error('UPI: Token Request Failed:- ' . ($data['errorMsg']));
                return null;
            }
        } catch (RequestException $e) {
            Log::error('UPI: Token Creation Failed:- ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            Log::error('UPI: Token Creation Failed:- ' . $e->getMessage());
            return null;
        }
    }

    public function payoutAccessToken()
    {
        $client = new Client();
        try {
            $response = $client->post($this->payoutUrl . "/v1/auth/realms/owlx/protocol/openid-connect/token", [
                'headers' => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
                'form_params' => [
                    "client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "grant_type" => "client_credentials",
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                return $data['access_token'];
            } else {
                Log::error('UPI: Payout Token Request Failed:- ' . ($data));
                return null;
            }
        } catch (RequestException $e) {
            Log::error('UPI: Payout Token Creation Failed:- ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            Log::error('UPI: Payout Token Creation Failed:- ' . $e->getMessage());
            return null;
        }
    }

    public function generateChecksum($payload)
    {
        $token = $this->accessToken();
        $client = new Client();
        try {
            $response = $client->post($this->baseUrl . "/1.0/checksum", [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => [
                    "payload" => $payload,
                    "checksum" => $this->checkSum
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                return $data['response']['checksum'];
            } else {
                Log::error('UPI: Checksum Request Failed:- ' . ($data['errorMsg']));
                return null;
            }
        } catch (RequestException $e) {
            Log::error('UPI: Checksum Creation Failed:- ' . $e->getMessage());
            return null;
        } catch (Exception $e) {
            Log::error('UPI: Checksum Creation Failed:- ' . $e->getMessage());
            return null;
        }
    }

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = UPIPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'amount'    => 'required|string',
            'currency' => 'required|in:INR',
            'method' => 'required|in:QR,UPI',
            'url' => 'required|url',
            'description' => 'required|string',
        ]);

        $vpaTotalAmount = Transaction::where('card_number', $checkacc->vpa)
            ->whereIn('payment_status', ['Pending', 'Completed'])
            ->where('status', 'p23')
            ->sum('amount');

        $vpaLimit = UpiMerchant::where('vpa', $checkacc->vpa)->first()['limitPerDay'] ?? 0;

        if ($vpaLimit > 0 && ($vpaTotalAmount + $validated['amount']) > $vpaLimit) {
            UpiMerchant::where('vpa', $checkacc->vpa)->update([
                'status' => '0',
            ]);

            $newMerchant = UpiMerchant::where('status', '1')
                ->where('vpa', '!=', $checkacc->vpa)
                ->inRandomOrder()
                ->first();

            if ($newMerchant) {
                $checkacc->mid = $newMerchant->mid;
                $checkacc->vpa = $newMerchant->vpa;
            } else {
                $checkacc->mid = null;
                $checkacc->vpa = null;
            }
            $checkacc->save();
        }

        if (!$checkacc->mid || !$checkacc->vpa) {
            Log::warning("No active merchant available for Account ID: " . $accId);
            return response()->json(['error' => 'Something went wrong, please try again!'], 503);
        }


        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientRefId = 'Refx' . uniqid();

        if ($validated['method'] === 'QR') {
            $payload = [
                "merchantVpa" => $checkacc->vpa,
                "merchantName" => $this->merchantName,
                "mid" => $checkacc->mid,
                "minAmount" => $validated['amount'],
                "amount" => $validated['amount'],
                "url" => $validated['url'],
                "note" => $validated['description'],
                "referenceNo" => $clientRefId,
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes')
            ];

            $path = $this->baseUrl . "/1.2/upi/generateqr/" . $this->bpmidentifier . "/" . $clientRefId;
        } else {
            $payload = [
                "merchantVpa" => $checkacc->vpa,
                "mid" => $checkacc->mid,
                "amount" => $validated['amount'],
                "note" => $validated['description'],
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes')
            ];

            $path = $this->baseUrl . "/1.2/upi/intent/" . $this->bpmidentifier . "/" . $clientRefId;
        }


        $token = $this->accessToken();
        $checksum = $this->generateChecksum($payload);

        if (!$token || !$checksum) {
            return response()->json(['error' => 'Something went wrong, please try again!'], 500);
        }

        $client = new Client();
        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Authorization' => 'Bearer ' . $token,
                    'ChannelID' => $this->channelId,
                ],
                'json' => [
                    "payload" => $payload,
                    "checksum" => $checksum
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {

                if ($validated['method'] === 'QR') {
                    $paymentData = str_replace(' ', '', $data['response']['qrString']);
                    $type = 'QR';
                } else {
                    $paymentData = str_replace(' ', '', $data['response']['intentUrl']);
                    $type = 'INTENT';
                }

                $token = Crypt::encryptString(json_encode([
                    'checkout_id' => $uuid,
                    'type' => $type,
                    'data' => $paymentData,
                    'expires_at' => now()->addMinutes(config('services.p23.payment_expiry_minutes'))->timestamp,
                ]));

                $payUrl = route('p23.payment.page', [
                    'checkout_id' => $uuid,
                ]) . '?' . http_build_query([
                    'token' => $token,
                ]);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $validated['currency'];
                $trans->amount         = $validated['amount'];
                $trans->checkout_id    = $uuid;
                $trans->payment_id      = $data['txnId'];
                $trans->payment_status = 'Pending';
                $trans->description     = $validated['description'];
                $trans->card_number     = $checkacc->vpa;
                $trans->status         = 'p23';
                $trans->customer_details  = $clientRefId;

                $trans->save();

                Log::info("UPI: Payin Initialization with Checkout ID:- " . $uuid);

                $responseData = [
                    "success"     => true,
                    "amount"      => $validated['amount'],
                    "currency"    => $validated['currency'],
                    "checkout_id" => $uuid,
                    "link"        => $payUrl,
                ];

                return response()->json($responseData, 200);
            } else {
                Log::error('UPI: Checkout Request Failed:- ' . ($data['errorMsg']));
                return response()->json(['error' => 'Failed to create checkout'], 500);
            }
        } catch (RequestException $e) {
            Log::error('UPI: Checkout Creation Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create checkout'], 500);
        } catch (Exception $e) {
            Log::error('UPI: Checkout Creation Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create checkout'], 500);
        }
    }

    public function retryCheckout(Request $request, $checkout_id)
    {
        $oldTransaction = Transaction::where('checkout_id', $checkout_id)
            ->where('status', 'p23')
            ->first();

        if (!$oldTransaction) {
            abort(403, 'Invalid checkout request.');
        }

        $checkacc = UPIPayment::where('accountId', $oldTransaction->account_id)
            ->where('status', '1')
            ->first();

        if ($oldTransaction->payment_status == 'Failed') {
            $newMerchant = UpiMerchant::where('status', '1')
                ->where('vpa', '!=', $oldTransaction->card_number)
                ->inRandomOrder()
                ->first();

            if ($newMerchant) {
                $checkacc->mid = $newMerchant->mid;
                $checkacc->vpa = $newMerchant->vpa;
            } else {
                $checkacc->mid = null;
                $checkacc->vpa = null;
            }
            $checkacc->save();
        }

        if (!$checkacc->mid || !$checkacc->vpa) {
            Log::warning("No active merchant available for Account ID: " . $checkacc->accountId);
            abort(503, 'Something went wrong, please try again!');
        }

        $method = '';

        if ($request->filled('token')) {
            $oldPayload = json_decode(Crypt::decryptString($request->token), true);

            if (($oldPayload['type'] ?? null) === 'QR') {
                $method = 'QR';
            } else {
                $method = 'UPI';
            }
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientRefId = 'Refx' . uniqid();
        $amount = (string) (int) $oldTransaction->amount;
        $currency = $oldTransaction->currency;
        $description = $oldTransaction->description;

        if ($method === 'QR') {
            $payload = [
                "merchantVpa" => $checkacc->vpa,
                "merchantName" => $this->merchantName,
                "mid" => $checkacc->mid,
                "minAmount" => $amount,
                "amount" => $amount,
                "url" => $checkacc->redirect_url,
                "note" => $oldTransaction->description,
                "referenceNo" => $clientRefId,
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes')
            ];

            $path = $this->baseUrl . "/1.2/upi/generateqr/" . $this->bpmidentifier . "/" . $clientRefId;
        } else {
            $payload = [
                "merchantVpa" => $checkacc->vpa,
                "mid" => $checkacc->mid,
                "amount" => $amount,
                "note" => $oldTransaction->description,
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes')
            ];

            $path = $this->baseUrl . "/1.2/upi/intent/" . $this->bpmidentifier . "/" . $clientRefId;
        }

        $token = $this->accessToken();
        $checksum = $this->generateChecksum($payload);

        if (!$token || !$checksum) {
            abort(403, 'Something went wrong.');
        }

        $client = new Client();

        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Authorization' => 'Bearer ' . $token,
                    'ChannelID' => $this->channelId,
                ],
                'json' => [
                    "payload" => $payload,
                    "checksum" => $checksum
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {

                if ($method === 'QR') {
                    $paymentData = str_replace(' ', '', $data['response']['qrString']);
                    $type = 'QR';
                } else {
                    $paymentData = str_replace(' ', '', $data['response']['intentUrl']);
                    $type = 'INTENT';
                }

                $token = Crypt::encryptString(json_encode([
                    'checkout_id' => $uuid,
                    'type' => $type,
                    'data' => $paymentData,
                    'expires_at' => now()->addMinutes(config('services.p23.payment_expiry_minutes'))->timestamp,
                ]));

                $payUrl = route('p23.payment.page', [
                    'checkout_id' => $uuid,
                ]) . '?' . http_build_query([
                    'token' => $token,
                ]);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $currency;
                $trans->amount         = $amount;
                $trans->checkout_id    = $uuid;
                $trans->payment_id      = $data['txnId'];
                $trans->payment_status = 'Pending';
                $trans->description     = $description;
                $trans->card_number     = $checkacc->vpa;
                $trans->status         = 'p23';
                $trans->customer_details  = $clientRefId;

                $trans->save();

                Log::info("UPI: Payin Initialization with Checkout ID:- " . $uuid);

                return redirect($payUrl);
            } else {
                Log::error('UPI: Checkout Request Failed:- ' . ($data['errorMsg']));
                abort(403, 'Checkout request failed.');
            }
        } catch (RequestException $e) {
            Log::error('UPI: Checkout Creation Failed:- ' . $e->getMessage());
            abort(403, 'Checkout request failed.');
        } catch (Exception $e) {
            Log::error('UPI: Checkout Creation Failed:- ' . $e->getMessage());
            abort(403, 'Checkout request failed.');
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

        $transaction = Transaction::where('checkout_id', $checkout_id)->where('status', 'p23')->first();


        $createdAt = $transaction->created_at->timestamp;
        $expiresAt = $createdAt + (config('services.p23.payment_expiry_minutes') * 60); // expiry minutes
        $isExpired = time() > $expiresAt;

        if ($isExpired && $transaction->payment_status == 'Pending') {
            $transaction->payment_status = 'Expired';
            $transaction->save();
        }

        return view('payment.upi.checkout', compact('checkout_id', 'type', 'transaction', 'isExpired', 'expiresAt', 'paymentData'));
    }

    public function createPayout(Request $request, $accId)
    {
        $checkacc = UPIPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'name'          => 'required|string',
            'email'         => 'required|email',
            'phone'         => 'required|digits:10',
            'amount'        => 'required|numeric|min:500|max:1000000|regex:/^\d+(\.\d{1,2})?$/',
            'currency'      => 'required|in:INR',
            'bankName'      => 'required|string',
            'ifscCode'      => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'accountNo'     => 'required|digits_between:5,20',
            'modeOfPayment' => 'required|in:IMPS,NEFT,RTGS',
        ]);

        $token = $this->payoutAccessToken();

        if (!$token) {
            return response()->json(['error' => 'Something went wrong, please try again!'], 500);
        }

        $orderId = 'Ordx' . substr(bin2hex(random_bytes(13)), 0, 26);
        $userId = 'Usrx' . uniqid();

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $body = [
            "orderId" => $orderId,
            "debitFrom" => "",
            "userId" => $userId,
            "name" => $validated['name'],
            "email" => $validated['email'],
            "phone" => $validated['phone'],
            "bankName" => $validated['bankName'],
            "ifscCode" => $validated['ifscCode'],
            "accountNo" => $validated['accountNo'],
            "amount" => $validated['amount'],
            "modeOfPayment" => $validated['modeOfPayment'],
            "notifyUrl" => "https://gatewayeng.azure-api.net/payout/upi/payout/webhook/upi",
        ];

        $client = new Client();
        try {
            $response = $client->post($this->payoutUrl . "/v1/payout/create", [
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . $token,
                    "Ocp-Apim-Subscription-Key" => $this->subscriptionKey,
                    "ChannelID" => $this->channelId,
                ],
                'json' => $body,
            ]);

            $data = json_decode($response->getBody(), true);
            if ($response->getStatusCode() == 200) {
                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $validated['currency'];
                $trans->amount         = $validated['amount'];
                $trans->checkout_id    = $uuid;
                $trans->payment_status = 'Pending';
                $trans->description     = 'Payout order initiated: ' . $orderId;
                $trans->status         = 'p23';
                $trans->customer_details  = $userId;

                $trans->save();

                Log::info("UPI: Payout Initialization with Checkout ID:- " . $uuid);

                $responseData = [
                    "success"     => true,
                    "amount"      => $validated['amount'],
                    "currency"    => $validated['currency'],
                    "checkout_id" => $uuid,
                    "order_id"  => $orderId,
                    'status'      => 'Pending',
                ];

                return response()->json($responseData, 200);
            } else {
                Log::error('UPI: Payout Request Failed:- ' . ($data));
                return response()->json(['error' => 'Failed to create payout'], 500);
            }
        } catch (RequestException $e) {
            Log::error('UPI: Payout Creation Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create payout'], 500);
        } catch (Exception $e) {
            Log::error('UPI: Payout Creation Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create payout'], 500);
        }
    }

    public function payinNotification(Request $request)
    {
        Log::info('Upi: Payin webhook request', [
            'payload' => $request->all(),
        ]);

        $trans = Transaction::where('customer_details', $request->input('clientRefId'))->where('status', 'p23')->first();

        if (!$trans) {
            Log::warning("Transaction not found for Client Ref: " . $request->input('clientRefId'));
            return;
        }

        $trans->payer_details = $request->input('payerDtls') ?? null;
        $trans->token = $request->input('rrn');

        $status = $request->input('status');
        if (strtolower($status) === 'success') {
            $trans->payment_status = 'Completed';
        } elseif (strtolower($status) === 'failure') {
            $trans->payment_status = 'Failed';
        } else {
            $trans->payment_status = ucfirst(strtolower($status));
        }

        $trans->status  = 'p23';

        $trans->save();

        $account = UPIPayment::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/finpay/p23/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("p23 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("p23 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function payoutNotification(Request $request)
    {
        Log::info('Upi: Payout webhook request', [
            'payload' => $request->all(),
        ]);

        $orderId = $request->input('orderId');

        $trans = Transaction::where('description', 'Payout order initiated: ' . $orderId)->where('status', 'p23')->first();

        if (!$trans) {
            Log::warning("Order not found for ID: " . $request->input('orderId'));
            return;
        }

        $trans->payment_id     = $request->input('utrNo');
        $trans->description     = 'Payout completed: ' . $request->input('orderId');

        $status = $request->input('status');

        if (strtolower($status) === 'success') {
            $trans->payment_status = 'Completed';
        } else {
            $trans->payment_status = ucfirst(strtolower($status));
        }

        $trans->status         = 'p23';

        $trans->save();

        $account = UPIPayment::where('accountId', $trans->account_id)->first();
        if (!isset($account)) {
            Log::warning("Account not found");
        }

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => $account->b_token,
            ];

            $webhook = new Client();
            $resp = $webhook->get($account->redirect_url . '/api/finpay/p23/' . $trans->checkout_id, [
                'headers' => $headers,
                'timeout' => 15,
            ]);
            Log::info("p23 forward OK response from client, status: {$resp->getStatusCode()}");
        } catch (RequestException $e) {
            Log::warning("p23 forward api to client failed: " . $e->getMessage());
        }

        return response()->json([
            "success" => true,
        ], 200);
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = UPIPayment::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p23')->first();
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

    public function getPayinStatus($checkout_id)
    {
        $trans = Transaction::where('checkout_id', $checkout_id)->where('status', 'p23')->first();
        $client = new Client();
        $path = $this->baseUrl . "/1.0/checktxndetails";

        $payload = [
            "txnid" => $trans->payment_id,
            "clientrefid" => $trans->customer_details,
        ];

        $token = $this->accessToken();
        $checkSum = $this->generateChecksum($payload);

        if (!$token || !$checkSum) {
            Log::error('UPI: Token or Checksum Creation Failed:- Unable to get access token or checksum for transaction status update.');
        }

        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => [
                    "payload" => [
                        "txnid" => $trans->payment_id,
                        "clientrefid" => $trans->customer_details,
                    ],
                    "checksum" => $checkSum
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                $status = $data['response']['txnStatus'];

                if (strtolower($status) === 'success') {
                    $trans->payment_status = 'Completed';
                } elseif (strtolower($status) === 'generated') {
                    $trans->payment_status = 'Pending';
                } else {
                    $trans->payment_status = ucfirst(strtolower($status));
                }

                $trans->save();

                return response()->json([
                    "success" => true,
                    "checkout_id" => $trans->checkout_id,
                    "payment_id" => $trans->payment_id,
                    "status" => $trans->payment_status,
                ]);
            } else {
                Log::error('UPI: Transaction Status Request Failed:- ' . json_encode($data));

                return response()->json([
                    "success" => false,
                    "checkout_id" => $trans->checkout_id,
                    "payment_id" => $trans->payment_id,
                    "status" => $trans->payment_status,
                ]);
            }
        } catch (RequestException $e) {
            Log::warning("UPI: Transaction Status Update Failed: " . $e->getMessage());

            return response()->json([
                "success" => false,
                "checkout_id" => $trans->checkout_id,
                "payment_id" => $trans->payment_id,
                "status" => $trans->payment_status,
            ]);
        }
    }

    public function markPayinExpired($checkout_id)
    {
        $transaction = Transaction::where('checkout_id', $checkout_id)
            ->where('status', 'p23')
            ->first();

        if (!in_array($transaction->payment_status, ['Completed', 'Failed'])) {
            $transaction->payment_status = 'Expired';
            $transaction->save();
        }

        return response()->json([
            'success' => true,
            'status' => $transaction->payment_status,
        ]);
    }

    public function updateP23TrxnStatus($checkout_id)
    {
        $trans = Transaction::where('checkout_id', $checkout_id)->where('status', 'p23')->first();
        $client = new Client();

        if ($trans && $trans->payment_id) {
            $path = $this->baseUrl . "/1.0/checktxndetails";

            $payload = [
                "txnid" => $trans->payment_id,
                "clientrefid" => $trans->customer_details,
            ];

            $token = $this->accessToken();
            $checkSum = $this->generateChecksum($payload);

            if (!$token || !$checkSum) {
                Log::error('UPI: Token or Checksum Creation Failed:- Unable to get access token or checksum for transaction status update.');
                return back()->with('error', 'Failed to update transaction status.');
            }

            try {
                $response = $client->post($path, [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'json' => [
                        "payload" => [
                            "txnid" => $trans->payment_id,
                            "clientrefid" => $trans->customer_details,
                        ],
                        "checksum" => $checkSum
                    ],
                ]);
                $data = json_decode($response->getBody(), true);
                $statusCode = $response->getStatusCode();

                if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                    $status = $data['response']['txnStatus'];

                    if (strtolower($status) === 'success') {
                        $trans->payment_status = 'Completed';
                    } elseif (strtolower($status) === 'generated') {
                        $trans->payment_status = 'Pending';
                    } else {
                        $trans->payment_status = ucfirst(strtolower($status));
                    }

                    $createdAt = $trans->created_at->timestamp;
                    $expiresAt = $createdAt + (config('services.p23.payment_expiry_minutes') * 60); // expiry minutes
                    $isExpired = time() > $expiresAt;

                    if ($isExpired && $trans->payment_status == 'Pending') {
                        $trans->payment_status = 'Expired';
                    }

                    $trans->save();

                    return back()->with('success', 'Transaction status updated successfully.');
                } else {
                    Log::error('UPI: Transaction Status Request Failed:- ' . json_encode($data));
                    return back()->with('error', 'Failed to update transaction status.');
                }
            } catch (RequestException $e) {
                Log::warning("UPI: Transaction Status Update Failed: " . $e->getMessage());
                return back()->with('error', 'Failed to update transaction status.');
            }
        } else {
            $path = $this->payoutUrl . "/v1/payout/checkOrderStatus";
            $token = $this->payoutAccessToken();

            if (!$token) {
                Log::error('UPI: Payout Token Creation Failed:- Unable to get access token for payout status update.');
                return back()->with('error', 'Failed to update transaction status.');
            }

            $orderId = str_replace(
                ['Payout order initiated: ', 'Payout completed: '],
                '',
                $trans->description
            );

            try {
                $response = $client->post($path, [
                    'headers' => [
                        "Content-Type" => "application/json",
                        "Authorization" => "Bearer " . $token,
                        "Ocp-Apim-Subscription-Key" => $this->subscriptionKey,
                    ],
                    'json' => [
                        "orderId" => $orderId,
                    ],
                ]);

                $data = json_decode($response->getBody(), true);
                $statusCode = $response->getStatusCode();

                if ($statusCode === 200) {
                    $trans->payment_id = $data['data']['transactionId'] ?? null;

                    $status = $data['data']['status'];

                    if (strtolower($status) === 'success') {
                        $trans->payment_status = 'Completed';
                    } else {
                        $trans->payment_status = ucfirst(strtolower($status));
                    }

                    $trans->description = 'Payout ' . $status . ': ' . $orderId;
                    $trans->save();

                    return back()->with('success', 'Transaction status updated successfully.');
                } else {
                    Log::error('UPI: Payout Status Request Failed:- ' . json_encode($data));
                    return back()->with('error', 'Failed to update transaction status.');
                }
            } catch (RequestException $e) {
                Log::warning("UPI: Payout status update failed:- " . $e->getMessage());
                return back()->with('error', 'Failed to update transaction status.');
            }
        }
    }
}
