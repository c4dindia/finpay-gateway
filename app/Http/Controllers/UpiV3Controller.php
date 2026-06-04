<?php

namespace App\Http\Controllers;

use App\Models\Company;
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

class UpiV3Controller extends Controller
{
    protected $baseUrl = "https://payment-gateway.in/upi/api/2.12";
    protected $subscriptionKey = "1ceb19d850404bac9ae417b1ba0a4191";
    protected $bpmidentifier = "bpm0003";
    protected $channelId = "TG2";
    protected $merchantName = "Suryoday";

    protected $clientId = "3c083382-f3e6-435b-b80e-3ce71be7645e";
    protected $clientSecret = "94ebaBbH9hKFzgx6BK8w4q63AzdNYUqa";

    public function createCheckoutV3(Request $request, $accId)
    {
        $checkacc = UPIPayment::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $validated = $request->validate([
            'amount'    => 'required|numeric|min:10|max:7000',
            'currency'  => 'required|in:INR',
            'method'    => 'required|in:QR,UPI',
            'url'       => 'required|url',
            'description' => 'required|regex:/^[A-Za-z0-9 ]+$/',
            'mobile'    => 'required|digits:10',
            'email'     => 'required|email',
        ], [
            'description.regex' => 'Description must contain only letters and numbers.',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientRefId = 'Refx' . substr(str_replace('-', '', Str::uuid()->toString()), 0, 26);
        $midv3 = $checkacc->midv3;

        if (!$midv3) {
            Log::error("UPI: Merchant V3 ID not found");
            return response()->json(['error' => 'Something went wrong'], 400);
        }

        if ($validated['method'] === 'QR') {
            $payload = [
                "merchantName"  => $this->merchantName,
                "mid"           => $checkacc->midv3,
                "minAmount"     => $validated['amount'],
                "amount"        => $validated['amount'],
                "url"           => $validated['url'],
                "note"          => $validated['description'],
                "referenceNo" => $clientRefId,
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes'),
                "udf1"        => $validated['mobile'],   //mobile no.
                "udf2"        => $validated['email'],    //email
            ];

            $path = $this->baseUrl . "/upi/generateqr/" . $this->bpmidentifier . "/" . $clientRefId;
        } else {
            $payload = [
                "merchantName" => $this->merchantName,      //added
                "mid" => $checkacc->midv3,
                "amount" => $validated['amount'],
                "note" => $validated['description'],
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes'),
                "udf1"        => $validated['mobile'],   //mobile no.
                "udf2"        => $validated['email'],    //email
            ];

            $path = $this->baseUrl . "/upi/intent/" . $this->bpmidentifier . "/" . $clientRefId;
        }

        $client = new Client();
        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'ChannelID' => $this->channelId,
                ],
                'json' => [
                    "payload" => $payload,
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
                    'mobile' => $validated['mobile'],
                    'email' => $validated['email'],
                    'data' => $paymentData,
                    'expires_at' => now()->addMinutes(config('services.p23.payment_expiry_minutes'))->timestamp,
                ]));

                $payUrl = route('p23.payment.page-v3', [
                    'checkout_id' => $uuid,
                ]) . '?' . http_build_query([
                    'token' => $token,
                ]);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $validated['currency'];
                $trans->amount         = $validated['amount'];
                $trans->checkout_id    = $uuid;
                $trans->payment_id     = $data['response']['txnId'];
                $trans->payment_status = 'Pending';
                $trans->description    = $validated['description'];
                $trans->card_number    = $checkacc->midv3;
                $trans->status         = 'p23';
                $trans->customer_details  = $clientRefId;

                $trans->save();

                Log::info("UPI: v3 Payin Initialization with Checkout ID:- " . $uuid);

                $responseData = [
                    "success"     => true,
                    "amount"      => $validated['amount'],
                    "currency"    => $validated['currency'],
                    "checkout_id" => $uuid,
                    "link"        => $payUrl,
                ];

                return response()->json($responseData, 200);
            } else {
                Log::error('UPI: v3 Checkout Request Failed:- ' . ($data['errorMsg']));
                return response()->json(['error' => 'Failed to create checkout'], 500);
            }
        } catch (RequestException $e) {
            Log::error('UPI: v3 Checkout RequestException Failed:- ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create checkout'], 500);
        } catch (Exception $e) {
            Log::error('UPI: v3 Checkout Exception Failed:- ' . $e->getMessage());
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

        $method = '';
        $mobile = '';

        if ($request->filled('token')) {
            $oldPayload = json_decode(Crypt::decryptString($request->token), true);

            if (($oldPayload['type'] ?? null) === 'QR') {
                $method = 'QR';
            } else {
                $method = 'UPI';
            }

            $mobile = $oldPayload['mobile'];
            $email = $oldPayload['email'];
        }

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientRefId = 'Refx' . substr(str_replace('-', '', Str::uuid()->toString()), 0, 26);
        $amount = (string) (int) $oldTransaction->amount;
        $currency = $oldTransaction->currency;
        $description = $oldTransaction->description;

        if ($method === 'QR') {
            $payload = [
                "merchantName"  => $this->merchantName,
                "mid"           => $checkacc->midv3,
                "minAmount"     => $amount,
                "amount"        => $amount,
                "url"           => $checkacc->redirect_url,
                "note"          => $description,
                "referenceNo" => $clientRefId,
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes'),
                "udf1"        => $mobile,   //mobile no.
                "udf2"        => $email
            ];

            $path = $this->baseUrl . "/upi/generateqr/" . $this->bpmidentifier . "/" . $clientRefId;
        } else {
            $payload = [
                "merchantName" => $this->merchantName,      //added
                "mid" => $checkacc->midv3,
                "amount" => $amount,
                "note" => $description,
                "clientRefId" => $clientRefId,
                "expiryValue" => config('services.p23.payment_expiry_minutes'),
                "udf1"        => $mobile,   //mobile no.
                "udf2"        => $email
            ];

            $path = $this->baseUrl . "/upi/intent/" . $this->bpmidentifier . "/" . $clientRefId;
        }

        $client = new Client();

        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'ChannelID' => $this->channelId,
                ],
                'json' => [
                    "payload" => $payload
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
                    'mobile' => $mobile,
                    'data' => $paymentData,
                    'expires_at' => now()->addMinutes(config('services.p23.payment_expiry_minutes'))->timestamp,
                ]));

                $payUrl = route('p23.payment.page-v3', [
                    'checkout_id' => $uuid,
                ]) . '?' . http_build_query([
                    'token' => $token,
                ]);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $currency;
                $trans->amount         = $amount;
                $trans->checkout_id    = $uuid;
                $trans->payment_id      = $data['response']['txnId'];
                $trans->payment_status = 'Pending';
                $trans->description     = $description;
                $trans->card_number    = $checkacc->midv3;
                $trans->status         = 'p23';
                $trans->customer_details  = $clientRefId;

                $trans->save();

                Log::info("UPI: V3 Payin Initialization with Checkout ID:- " . $uuid);

                return redirect($payUrl);
            } else {
                Log::error('UPI: V3 Checkout Request Failed:- ' . ($data['errorMsg']));
                abort(403, 'Checkout request failed.');
            }
        } catch (RequestException $e) {
            Log::error('UPI: V3 Checkout Creation Failed:- ' . $e->getMessage());
            abort(403, 'Checkout request failed.');
        } catch (Exception $e) {
            Log::error('UPI: V3 Checkout Creation Failed:- ' . $e->getMessage());
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

        return view('payment.upi.checkout-v3', compact('checkout_id', 'type', 'transaction', 'isExpired', 'expiresAt', 'paymentData'));
    }

    public function getPayinStatus($checkout_id)
    {
        $trans = Transaction::where('checkout_id', $checkout_id)->where('status', 'p23')->first();
        $client = new Client();
        $path = $this->baseUrl . "/checktxndetails";

        $payload = [
            "txnid" => $trans->payment_id,
            "clientrefid" => $trans->customer_details,
            "mid"   => UPIPayment::where('accountId', $trans->account_id)->first()->midv3
        ];

        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    "payload" => $payload,
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                $status = $data['response']['txnStatus'];

                if (strtolower($status) === 'success') {
                    $trans->payment_status = 'Completed';
                } elseif (strtolower($status) === 'generated' || strtolower($status) === 'transaction in process') {
                    $trans->payment_status = 'Pending';
                } elseif (strtolower($status) === 'incomplete' || strtolower($status) === 'failure') {
                    $trans->payment_status = 'Failed';
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
                Log::error('UPI: V3 Transaction Status Request Failed:- ' . json_encode($data));

                return response()->json([
                    "success" => false,
                    "checkout_id" => $trans->checkout_id,
                    "payment_id" => $trans->payment_id,
                    "status" => $trans->payment_status,
                ]);
            }
        } catch (RequestException $e) {
            Log::warning("UPI: V3 Transaction Status Update Failed: " . $e->getMessage());

            return response()->json([
                "success" => false,
                "checkout_id" => $trans->checkout_id,
                "payment_id" => $trans->payment_id,
                "status" => $trans->payment_status,
            ]);
        }
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

    public function paymentLink()
    {
        $accId = Company::where('user_id', auth()->id())->value('accountId');

        $midv3 = UPIPayment::where('accountId', $accId)->where('status', '1')->value('midv3');

        if (!$midv3) {
            abort(403, 'No active UPI merchant found.');
        }

        return view('payment.upi.payment-link-v3', compact('accId'));
    }

    public function generatePaymentLink(Request $request)
    {
        $checkacc = UPIPayment::where('accountId', $request->accId)->where('status', '1')->first();

        $validated = $request->validate([
            'amount'    => 'required|string',
            'currency' => 'required|in:INR',
            'description' => 'required|string',
            'phone' => 'required|digits:10',
            'email' => 'required|email',
            'url' => 'required|url',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $clientRefId = 'Refx' . substr(str_replace('-', '', Str::uuid()->toString()), 0, 26);
        $midv3 = $checkacc->midv3;

        if (!$midv3) {
            Log::error("UPI: Merchant V3 ID not found");
            return response()->json(['error' => 'Something went wrong'], 400);
        }

        $path = $this->baseUrl . "/upi/intent/" . $this->bpmidentifier . "/" . $clientRefId;

        $payload = [
            "merchantName" => $this->merchantName,      //added
            "mid" => $checkacc->midv3,
            "amount" => $validated['amount'],
            "note" => $validated['description'],
            "clientRefId" => $clientRefId,
            "expiryValue" => config('services.p23.payment_expiry_minutes'),
            "udf1"        => $validated['phone']   //mobile no.
        ];

        $client = new Client();
        try {
            $response = $client->post($path, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'ChannelID' => $this->channelId,
                ],
                'json' => [
                    "payload" => $payload
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200 && ($data['errorMsg'] ?? null) === "SUCCESS") {
                $paymentData = str_replace(' ', '', $data['response']['intentUrl']);
                $type = 'INTENT';

                $token = Crypt::encryptString(json_encode([
                    'checkout_id' => $uuid,
                    'type' => $type,
                    'mobile' => $validated['phone'],
                    'email' => $validated['email'],
                    'data' => $paymentData,
                    'expires_at' => now()->addMinutes(config('services.p23.payment_expiry_minutes'))->timestamp,
                ]));

                $payUrl = route('p23.payment.page-v3', [
                    'checkout_id' => $uuid,
                ]) . '?' . http_build_query([
                    'token' => $token,
                ]);

                $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p23')->first() ?: new Transaction();

                $trans->account_id     = $checkacc->accountId;
                $trans->currency       = $validated['currency'];
                $trans->amount         = $validated['amount'];
                $trans->checkout_id    = $uuid;
                $trans->payment_id      = $data['response']['txnId'];
                $trans->payment_status = 'Pending';
                $trans->description     = $validated['description'];
                $trans->card_number    = $checkacc->midv3;
                $trans->status         = 'p23';
                $trans->customer_details  = $clientRefId;
                $trans->save();

                Log::info("UPI: v3 Payin Initialization with Checkout ID:- " . $uuid);

                $responseData = [
                    "success"     => true,
                    "checkout_id" => $uuid,
                    "link"        => $payUrl,
                ];

                return response()->json($responseData, 200);
            } else {
                Log::error('UPI: v3 Payment Link Request Failed:- ' . ($data['errorMsg']));

                return response()->json([
                    'success' => false,
                    'error'   => 'Failed to create payment link',
                ], 400);
            }
        } catch (RequestException $e) {
            Log::error('UPI: v3 Payment Link Creation Failed:- ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error'   => 'Failed to create payment link'
            ], 500);
        } catch (Exception $e) {
            Log::error('UPI: v3 Payment Link Creation Failed:- ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error'   => 'Failed to create payment link'
            ], 500);
        }
    }
}
