<?php

namespace App\Http\Controllers;

use App\Models\Alikassa;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AlikassaController extends Controller
{
    protected $apiUrl = "https://api-merchant.alikassa.io";
    protected $payout_private_key = "storage/certs/alikassa/payout/private.pem";
    protected $payout_password = "storage/certs/alikassa/payout/password.txt";
    protected $payout_public_key = "storage/certs/alikassa/payout/public.pem";
    protected $notification_public_key = "storage/certs/alikassa/notification/public.pem";

    protected $notification_endpoint_id = 1146;  //  1146 : 'https://payment.ryzen-pay.com/api/p21/notification'

    public function createCheckout(Request $request, $accId)
        {
           $checkacc = Alikassa::where('accountId', $accId)->where('status', '1')->first();

           if (!$checkacc) {
               return response()->json(['error' => 'Unauthorized Account'], 401);
           }
           $accountUUID = $checkacc->alikassa_uuid ;
           if (!isset($accountUUID)){
                return response()->json(['error' => 'MID not assigned for this service'], 401);
           }

           $apiUrl = $this->apiUrl;
           $privateKeyConfig = $this->payout_private_key;
           $privateKeyPasswordConfig = $this->payout_password;

           do {
                $checkoutId = Str::uuid()->toString();
            } while (Transaction::where('checkout_id', $checkoutId)->exists());

            $request->validate([
               'amount' => 'required|numeric',  // |min:30000|max:300000
               'number' => 'required|string|digits_between:12,19',  //card no.
               'extra' => 'nullable|array',
               'extra.card_exp_year' => 'nullable|string|size:2',
               'extra.card_exp_month' => 'nullable|string|size:2',
               'extra.card_holder' => 'nullable|string|max:100',
               'extra.card_country' => 'nullable|string|size:2',
               'extra.card_recipient_birth_date' => 'nullable|date_format:Y-m-d',
               'customer_phone' => 'nullable|string|max:20',
               'customer_email' => 'nullable|email|max:255',
               'customer_code' => 'nullable|string|max:255',
               'customer_first_name' => 'nullable|string|max:255',
               'customer_last_name' => 'nullable|string|max:255',
               'customer_country' => 'nullable|string|max:3',
           ]);

           $requestBody = [
               'amount' => $request->amount,
               'number' => $request->number,
               'order_id' => $checkoutId,
               'service' => $checkacc->service,
               'notification_endpoint_id' => $this->notification_endpoint_id,
               'notification_endpoint_url' => 'https://payment.ryzen-pay.com/api/p21/notification',
           ];
           $requestBody = array_merge($requestBody, array_filter([
               'extra' => $request->extra ?? null,
               'customer_phone' => $request->customer_phone ?? null,
               'customer_email' => $request->customer_email ?? null,
               'customer_code' => $request->customer_code ?? null,
               'customer_first_name' => $request->customer_first_name ?? null,
               'customer_last_name' => $request->customer_last_name ?? null,
               'customer_country' => $request->customer_country ?? null,
           ], static fn ($value) => $value !== null && $value !== ''));

           $jsonPayload = json_encode($requestBody, JSON_UNESCAPED_SLASHES);

           try {
               $privateKeyContent = $this->resolveConfigValue($privateKeyConfig);

               $privateKeyPassword = $privateKeyPasswordConfig !== '' ? $this->resolveConfigValue($privateKeyPasswordConfig) : null;
               $privateKey = openssl_pkey_get_private($privateKeyContent, $privateKeyPassword ?: null);

               if ($privateKey === false) {
                   return response()->json([
                       'success' => false,
                       'message' => 'Invalid payout private key or password',
                   ], 500);
               }

               openssl_sign($jsonPayload, $signature, $privateKey);
               $signatureBase64 = base64_encode($signature);

               $response = Http::withHeaders([
                   'Content-Type' => 'application/json',
                   'Account' => $accountUUID,
                   'Sign' => $signatureBase64,
               ])->withBody($jsonPayload, 'application/json')->timeout(15)->post($apiUrl . '/v1/payout');

               Log::channel('alikassa')->info('createPayment request', [
                'response_body' => $response->body()
            ]);

               if ($response->successful()) {
                   $result = (array) ($response->json() ?? []);
                   $providerPaymentId = (string) ($result['id'] ?? '');
                   $providerStatus = $result['payment_status'] ?? null;

                   $trxn = Transaction::where('checkout_id', $checkoutId)->where('status', 'p21')->first() ?: new Transaction();
                   $trxn->account_id = $checkacc->accountId;
                   $trxn->currency = 'KZT';
                   $trxn->amount = (int) $request->amount;
                   $trxn->from_currency = $checkacc->currency;
                   $trxn->from_amount = (int) $request->amount;
                   $trxn->checkout_id = $checkoutId;
                   $trxn->payment_id = $providerPaymentId !== '' ? $providerPaymentId : null;
                   $trxn->payment_status = ucfirst(strtolower($providerStatus));
                   $trxn->description = 'Payout';
                   $trxn->customer_details = trim(($request->customer_first_name ?? '') . ' ' . ($request->customer_last_name ?? '')) !== '' ? ('Name: ' . trim(($request->customer_first_name ?? '') . ' ' . ($request->customer_last_name ?? ''))) : null;
                   $trxn->status = 'p21';
                   $trxn->save();

                   $responseData = [
                       'success' => true,
                       'checkout_id' => $checkoutId,
                       'payment_id' => $providerPaymentId,
                       'currency' => $trxn->currency,
                       'amount' => number_format((float) $request->amount, 2, '.', ''),
                       'account_number' => $request->number,
                       'payment_status' => ucfirst(strtolower($providerStatus)),
                       'customer_first_name' => $request->customer_first_name,
                       'customer_last_name' => $request->customer_last_name,
                       'customer_email' => $request->customer_email,
                       'customer_phone' => $request->customer_phone,
                       'customer_country' => $request->customer_country,
                   ];
                   return response()->json(array_filter($responseData, static fn ($value) => $value !== null && $value !== ''), 200);
               }
               return response()->json([
                   'success' => false,
                   'message' => 'Checkout Request has Failed',
                   'error' => $response->json() ?: $response->body(),
               ], $response->status());
           } catch (\Throwable $e) {
               Log::channel('alikassa')->error('AliKassa create checkout exception', [
                   'error' => $e->getMessage(),
               ]);
               return response()->json([
                   'success' => false,
                   'message' => 'An error occurred while creating checkout',
                   'error' => $e->getMessage(),
               ], 500);
           }
    }
     public function getTransactionStatus($accId, $checkout_id)
       {
        $checkaccId = Alikassa::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p21')->first();
        if ($transaction == null) {
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'], 401);
        }

        return response()->json([
            'data' => [
                "currency" => $transaction->currency,
                "account_id" => $transaction->account_id,
                "amount" => number_format($transaction->amount, 2),
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description,
                "created_at" => $transaction->created_at
            ]
        ], 200);
    }
    private function resolveConfigValue(?string $value): string
    {
        $resolved = trim((string) $value);
        $basePathResolved = base_path($resolved);
        if (is_file($basePathResolved)) {
            return trim((string) file_get_contents($basePathResolved));
        }

        return '';
    }
    public function handleNotification(Request $request)
    {
        $rawBody = (string) $request->getContent();
        $payload = json_decode($rawBody, true) ?: [];
        $signatureVerified = null;
        $publicKey = $this->resolveConfigValue($this->notification_public_key);
        $signatureDecoded = !empty($payload['sign']) ? base64_decode((string) $payload['sign'], true) : false;
        if ($publicKey !== '' && $signatureDecoded !== false) {
            $notificationJson = json_encode($this->buildOrderedNotificationPayload($payload), JSON_UNESCAPED_SLASHES);
            $signatureVerified = $notificationJson !== false && openssl_verify($notificationJson, $signatureDecoded, $publicKey) === 1;

            if ($signatureVerified === false) {
                $rawPayload = json_decode($rawBody, true);
                if (is_array($rawPayload)) {
                    unset($rawPayload['sign']);
                    $rawNotificationJson = json_encode($rawPayload, JSON_UNESCAPED_SLASHES);
                    $signatureVerified = $rawNotificationJson !== false && openssl_verify($rawNotificationJson, $signatureDecoded, $publicKey) === 1;
                }
            }
        }
        //Webhook Recevied and Logged
        Log::channel('alikassa')->info('AliKassa webhook received', [
            'signature_verified' => $signatureVerified,
            'payload' => $payload,
        ]);
        $checkoutId = (string) ($payload['order_id'] ?? '');
        $accountUUID = (string) ($payload['account'] ?? '');
        $account = null;

        if ($accountUUID !== '') {
            $account = Alikassa::where('status', '1')->where('alikassa_uuid', $accountUUID)->first();
        }

        $trxn = null;
        if ($checkoutId !== '') {
            $trxn = Transaction::where('checkout_id', $checkoutId)->where('status', 'p21')->first() ?: new Transaction();
            if (!$trxn->account_id && $account) {
                $trxn->account_id = $account->accountId;
            }
            $trxn->currency = $trxn->currency ?: $account->currency ?? 'unknown';
            $resolvedAmount = $payload['payment_amount'] ?? ($payload['amount'] ?? $trxn->amount);
            if ($resolvedAmount !== null && $resolvedAmount !== '') {
                $trxn->amount = (int) $resolvedAmount;
            }
            $trxn->checkout_id = $checkoutId;
            if (!empty($payload['id'])) {
                $trxn->payment_id = (string) $payload['id'];
            }
            $trxn->payment_status = (string) ($payload['payment_status'] ?? $trxn->payment_status);
            $trxn->description = 'AliKassa Payout ' . ucfirst(strtolower((string) ($payload['payment_status'] ?? 'Updated')));
            $trxn->status = 'p21';
            $trxn->save();
        }
        if (!$account && $trxn && !empty($trxn->account_id)) {
            $account = Alikassa::where('status', '1')
                ->where('accountId', $trxn->account_id)
                ->first();
        }
        if ($account && $checkoutId !== '' && !empty($account->redirect_url)) {
            try {
                $headers = [
                    'Content-Type' => 'application/json',
                ];
                if (!empty($account->b_token)) {
                    $headers['Authorization'] = $account->b_token;
                }
                $resp = Http::withHeaders($headers)
                    ->timeout(15)
                    ->get($account->redirect_url . '/api/RyzenPay/p21/' . $checkoutId);

                Log::channel('alikassa')->info('P21 forward OK response from client', [
                    'status' => $resp->status(),
                    'checkout_id' => $checkoutId,
                ]);
            } catch (\Throwable $e) {
                Log::channel('alikassa')->warning('P21 forward api to client failed', [
                    'checkout_id' => $checkoutId,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::channel('alikassa')->warning('AliKassa webhook forward skipped', [
                'reason' => !$account ? 'account_not_found' : 'missing_checkout_or_redirect',
                'accountUUID' => $accountUUID,
                'checkout_id' => $checkoutId,
            ]);
        }
        return response()->json([
            'success' => true,
            'received' => true,
            'signature_verified' => $signatureVerified,
        ], 200);
    }
    public function updateP21TrxnStatus($trxn_id)
    {
        $trxn = Transaction::where('payment_id', $trxn_id)->where('status', 'p21')->first();
        if (!$trxn) {
            return back()->with('error', 'Transaction not found');
        }

        $accountRow = Alikassa::where('accountId', $trxn->account_id)->where('status', '1')->first();
        if (!$accountRow) {
            return back()->with('error', 'AliKassa account not found');
        }

        $apiUrl = $this->apiUrl;
        $account = (string) ($accountRow->alikassa_uuid ?: ($accountRow->alikassa_id ?: (config('services.alikassa.uuid') ?: config('services.alikassa.id'))));
        $privateKeyConfig = (string) config('services.alikassa.payout_private_key');
        $privateKeyPasswordConfig = (string) config('services.alikassa.payout_password');

        $payload = ['id' => (string) $trxn_id];
        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);

        try {
            $privateKeyContent = $this->resolveConfigValue($privateKeyConfig);
            $privateKeyPassword = $privateKeyPasswordConfig !== '' ? $this->resolveConfigValue($privateKeyPasswordConfig) : null;
            $privateKey = openssl_pkey_get_private($privateKeyContent, $privateKeyPassword ?: null);
            if ($privateKey === false || $jsonPayload === false) {
                return back()->with('error', 'Invalid AliKassa certificate configuration');
            }

            openssl_sign($jsonPayload, $signature, $privateKey);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Account' => $account,
                'Sign' => base64_encode($signature),
            ])->withBody($jsonPayload, 'application/json')
                ->timeout(30)
                ->post($apiUrl . '/v1/payout/status');

            Log::channel('alikassa')->info('AliKassa payout status refresh', [
                'payment_id' => $trxn_id,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            if (!$response->successful()) {
                return back()->with('error', 'Failed to fetch payout status');
            }

            $result = (array) ($response->json() ?? []);
            if (!empty($result['payment_status'])) {
                $trxn->payment_status = ucfirst(strtolower((string) $result['payment_status']));
                $trxn->description = 'AliKassa Payout ' . $trxn->payment_status;
            }
            $amount = $result['payment_amount'] ?? ($result['amount'] ?? null);
            if ($amount !== null && $amount !== '') {
                $trxn->amount = (int) $amount;
            }
            $trxn->save();

            return back()->with('success', 'Status Updated!');
        } catch (\Throwable $e) {
            Log::channel('alikassa')->error('AliKassa payout status refresh exception', [
                'payment_id' => $trxn_id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'An error occurred while finding payout status');
        }
    }
    private function buildOrderedNotificationPayload(array $query): array
    {
        $isPartialPayment = $query['is_partial_payment'] ?? null;
        if (is_string($isPartialPayment)) {
            $isPartialPayment = match (strtolower(trim($isPartialPayment))) {
                'true', '1' => true,
                'false', '0' => false,
                default => $isPartialPayment,
            };
        }

        return [
            'type' => $query['type'] ?? null,
            'id' => isset($query['id']) ? (int) $query['id'] : null,
            'order_id' => $query['order_id'] ?? null,
            'payment_status' => $query['payment_status'] ?? null,
            'amount' => $query['amount'] ?? null,
            'payment_amount' => $query['payment_amount'] ?? null,
            'commission_amount' => $query['commission_amount'] ?? null,
            'is_partial_payment' => $isPartialPayment,
            'account' => $query['account'] ?? null,
            'service' => $query['service'] ?? null,
            'desc' => $query['desc'] ?? null,
            'card_brand' => $query['card_brand'] ?? null,
        ];
    }
}
