<?php

namespace App\Http\Controllers;

use App\Models\Direpay;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DirepayController extends Controller
{
    // protected $baseURL = 'https://api.uat.direpay.app'; //sandbox
    protected $baseURL = 'https://api.direpay.app';        // production

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = Direpay::where('accountId', $accId)->where('status', '1')->first();
        if (!$checkacc) {
            return response()->json(['error' => 'Unauthorized Account', 'accountIdEntered' => $accId  , ], 401);
        }

        if (! isset($checkacc->direpay_api_email)) {
            return response()->json(['error' => 'MID not assigned yet'], 401);
        }

        $request->validate([
            'redirect_url' => 'nullable|url',
            'currency' => 'required|string',
            'amount' => 'required|numeric|min:100',
            'method' => ['required','string', Rule::in(['online_banking', 'qr', 'qr2', 'card', 'ewallet']) ],
            // 'customer_uid' => 'nullable|string',
            'depositor_name' => 'required|string',
            'txid' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'bank_account' => 'nullable|string',
        ],[
            'method.in' => 'Invalid payment method selected. Available methods are: online_banking, qr, qr2, card, ewallet.',
        ]);

        $allowedMethodsByCurrency = [
            'INR' => ['qr', 'qr2']
        ];
        $currency = strtoupper(trim((string) $request->input('currency')));
        $method = strtolower(trim((string) $request->input('method')));

        if (!isset($allowedMethodsByCurrency[$currency])) {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported currency',
                'allowed_currencies' => array_keys($allowedMethodsByCurrency),
            ], 422);
        }

        if (!in_array($method, $allowedMethodsByCurrency[$currency], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid method for selected currency',
                'currency' => $currency,
                'allowed_methods' => $allowedMethodsByCurrency[$currency],
            ], 422);
        }

        // $customerUid = 'CUST-' . str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        $bankCodeToNameMap = [
            'naged' => 'Naged',
            'bkash' => 'bKash',
        ];
        $bankNameToCodeMap = array_change_key_case(array_flip($bankCodeToNameMap), CASE_LOWER);

        $bankCode = strtolower(trim((string) $request->input('bank_code', '')));
        $bankName = strtolower(trim((string) $request->input('bank_name', '')));

        if ($bankCode !== '' && isset($bankCodeToNameMap[$bankCode])) {
            $request->merge([
                'bank_code' => $bankCode,
                'bank_name' => $bankCodeToNameMap[$bankCode],
            ]);
        } elseif ($bankName !== '' && isset($bankNameToCodeMap[$bankName])) {
            $resolvedBankCode = $bankNameToCodeMap[$bankName];
            $request->merge([
                'bank_code' => $resolvedBankCode,
                'bank_name' => $bankCodeToNameMap[$resolvedBankCode],
            ]);
        }

        do {
            $checkoutId = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $checkoutId)->exists());
        $merchantTxid = trim((string) $request->input('txid', ''));
        $txid = $merchantTxid !== '' ? $merchantTxid : ('TX' . time() . strtoupper(Str::random(6)));

        $apiUrl = rtrim($this->baseURL, '/');
        $secretKey = $checkacc->direpay_api_secret ?? null;
        if (empty($secretKey)) {
            return response()->json(['error' => 'Secret not configured'], 401);
        }

        try {
            $apiEmail = $checkacc->direpay_api_email ?? null;
            $apiPassword = $checkacc->direpay_api_password ?? null;
            if (empty($apiEmail) || empty($apiPassword)) {
                return response()->json([
                    "success" => false,
                    "message" => "MID not assigned"
                ],401);
            }

            $loginResponse = Http::post($apiUrl . '/api/login', [
                'email' => $apiEmail,
                'password' => $apiPassword
            ]);
            if (!$loginResponse->successful()) {
                // throw new \Exception('Failed to authenticate' . $loginResponse->body());
                return response()->json([
                    "success" => false,
                    "message" => "MID  details mismatch"
                ],401);
            }

            $token = $loginResponse->json('access_token');
            if (empty($token)) {
                return response()->json([
                    "success" => false,
                    "message" => "service provider token missing. Contact Support."
                ],402);
            }

            $timestamp = time();
            $requestData = [
                'command' => 'fiat_payment',
                'hashCode' => md5('fiat_payment' . $secretKey),
                'callback_url' => 'https://payzone.finpay.group/api/p17/notification',
                'redirect_url' => $request->input('redirect_url', 'https://www.ryzen-pay.com'),
                'currency' => $currency,
                'method' => $method,
                'customer_uid' => $checkoutId, //$customerUid,
                'depositor_name' => $request->input('depositor_name'),
                'amount' => $request->input('amount'),
                'timestamp' => $timestamp,
                'txid' => $txid,
            ];

            if($method != "qr"){
                $requestData['bank_code'] = $request->input('bank_code');
                $requestData['bank_name'] = $request->input('bank_name');
            }

            if ($request->filled('phone_number')) {
                $requestData['phone_number'] = $request->input('phone_number');
            }
            if ($request->filled('bank_account')) {
                $requestData['bank_account'] = $request->input('bank_account');
            }

            $filteredData = array_filter($requestData, fn($value) => $value !== '' && $value !== null && $value !== false);
            ksort($filteredData);
            $queryString = '';
            foreach ($filteredData as $key => $value) {
                $queryString .= ($queryString === '' ? '' : '&') . $key . '=' . $value;
            }
            $requestData['signature'] = strtolower(md5($queryString . '&secret=' . $secretKey));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($apiUrl . '/api/callBack', $requestData);

            Log::channel('direpay')->info('Payin request', [
                'account_id' => $checkacc->accountId,
                'request' => $requestData,
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseJson = (array) $response->json();
                $result = $responseJson['data'] ?? $responseJson;

                $paymentId = $result['txid'] ;
                $paylink = $result['pay_url'];

                //Saving Data in the DB
                $trxn = Transaction::where('checkout_id', $checkoutId)->where('status', 'p17')->first() ?: new Transaction();
                $trxn->account_id = $checkacc->accountId;
                $trxn->amount = $result['amount'] ?? $request->amount ?? null;
                $trxn->currency = $result['currency'] ?? $request->currency ?? null;
                $trxn->checkout_id = $checkoutId;
                $trxn->payment_id = $paymentId;
                $trxn->payment_status = 'Pending';
                $trxn->description = 'Transaction Created';
                $trxn->customer_details = "Name : ". $request->depositor_name ?? null;
                $trxn->status = 'p17';
                $trxn->save();

                Log::channel('direpay')->info("trxn saved from checkouut api  call");

                //API Response
                return response()->json([
                    'success' => true,
                    'amount' => number_format((float)($trxn->amount ?? 0), 2, '.', ''),
                    'currency' => $trxn->currency,
                    'checkout_id' => $checkoutId,
                    'payment_id' => $trxn->payment_id,
                    // 'customer_uid' => $customerUid,
                    'status' => $trxn->payment_status,
                    'link' => $paylink,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'request failed',
                'error' => $response->json() ?? $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            Log::channel('direpay')->error('Direpay checkout exception', [
                'account_id' => $accId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = Direpay::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p17')->first();
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

    public function handleNotification(Request $request)
    {
        $callbackData = $request->all();
        Log::channel('direpay')->info('Direpay Callback Received', [
            'callback_data' => $callbackData,
        ]);

        // $requiredFields = ['txid', 'type', 'customer_uid', 'request_amount', 'currency', 'actual_amount', 'status', 'method'];
        // foreach ($requiredFields as $field) {
        //     if (!array_key_exists($field, $callbackData)) {
        //         Log::channel('direpay')->error('Direpay Callback Missing Field', [
        //             'missing_field' => $field,
        //         ]);
        //         return response('Missing field: ' . $field, 200);
        //     }
        // }

        $txid = (string) $callbackData['txid'];
        $account =null;
        $transaction = Transaction::where('payment_id', $txid)->where('status', 'p17')->first() ?: new Transaction();
        if(isset($transaction->account_id)){
            $account = Direpay::where('accountId', $transaction->account_id)->first();
        }
        // $accountSecret = $account->direpay_api_secret;

        $callbackSignature = $request->header('Signature');
        if (!$callbackSignature) {
            Log::channel('direpay')->error('Direpay Callback Missing Signature', [
                'callback_data' => $callbackData,
            ]);
            // return response('false', 400);
        }

        // $expectedSignature = $this->generateCallbackSignature($callbackData, $accountSecret);
        // if (!hash_equals(strtolower((string) $callbackSignature), strtolower($expectedSignature))) {
        //     Log::channel('direpay')->error('Direpay Callback Invalid Signature', [
        //         'received_signature' => $callbackSignature,
        //         'expected_signature' => $expectedSignature,
        //         'callback_data' => $callbackData,
        //     ]);
        //     return response('false', 400);
        // }

        if(isset($account)){
            $transaction->account_id = $account->accountId;
        }

        $transaction->amount = $callbackData['actual_amount'] ?? $callbackData['request_amount'];
        $transaction->currency = $callbackData['currency'] ;
        $transaction->checkout_id = $transaction->checkout_id ?? 'checkout_id17_'. $txid;
        $transaction->payment_id = $txid;
        $transaction->payment_status = ucfirst(strtolower($callbackData['status']));
        $transaction->description = "Type: " . ($callbackData['type'] ?? 'N/A') . ". Method: " . ($callbackData['method'] ?? 'N/A');
        $transaction->status = 'p17';
        $transaction->save();

        Log::channel('direpay')->info('Transaction saved from webhoook');

        try {
            if ($account && $account->redirect_url && $account->b_token && $transaction) {
                Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => $account->b_token,
                ])->timeout(15)->get($account->redirect_url . '/api/finpay/p17/' . $transaction->checkout_id);
            }
        } catch (\Exception $e) {
            Log::channel('direpay')->warning('Direpay downstream forward failed', [
                'txid' => $txid,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(["success" => true], 200);
    }

    //update transaction status
     public function updateP17TrxnStatus($trxn_id)
    {
        $trxn = Transaction::where('payment_id', $trxn_id)->where('status', 'p17')->first();
        if (!$trxn) {
            return back()->with("error", "Transaction not found.");
        }

        $paymentMethodUser = Direpay::where('accountId', $trxn->account_id)->first();
        if (!$paymentMethodUser) {
            return back()->with("error", "Payment method configuration missing.");
        }

        try {
            $apiUrl = $this->baseURL;
            $secretKey = $paymentMethodUser->direpay_api_secret;
            $request = ['txid' => $trxn_id];

            $filteredData = array_filter($request, fn($v) => $v !== '' && $v !== null && $v !== false);
            ksort($filteredData);

            $queryString = http_build_query($filteredData);
            // $signature = strtolower(md5($queryString . '&secret=' . $secretKey));

            $requestData = [
                'command'  => 'fiat_payment_status',
                'hashCode' => md5('fiat_payment_status' . $secretKey),
                'txid'     => $trxn_id,
            ];

            ksort($requestData);

            $queryString = http_build_query($requestData);
            // $signature = strtolower(md5($queryString . '&secret=' . $secretKey));

            Log::channel('direpay')->info('Direpay Status Check Request', [
                'request_data' => $requestData,
                'url' => $apiUrl . '/api/callBack'
            ]);
            $token = $this->getBearerToken($paymentMethodUser->direpay_api_email,$paymentMethodUser->direpay_api_password);
            $response = Http::timeout(15)->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json'
                ])->post($apiUrl . '/api/callBack', $requestData);

            Log::channel('direpay')->info('Direpay Status Response', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            if (!$response->successful()) {
                return back()->with("error", "Status check failed!");
            }

            $responseBody = $response->json();
            if (!is_array($responseBody) || !isset($responseBody['status'])) {
                return back()->with("error", "Invalid API response.");
            }

            $trxn->payment_status = ucfirst(strtolower($responseBody['status']));
            $trxn->description = "Type: " . ($responseBody['type'] ?? 'N/A') . ". Method: " . ($responseBody['method'] ?? 'N/A');
            $trxn->save();

            return back()->with("success", "Status updated");

        } catch (\Exception $e) {

            Log::channel('direpay')->error('Direpay Status Check Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with("error", "Transaction not Updated!");
        }
    }

    private function getBearerToken($email, $secret): string
    {
        $apiUrl = $this->baseURL;
        $apiKey = $email;
        $secretKey = $secret;

        $response = Http::post($apiUrl . '/api/login', [
            'email' => $apiKey,
            'password' => $secretKey
        ]);

        Log::channel('direpay')->info('Direpay Login Attempt', [
            'url' => $apiUrl . '/api/login',
            'credentials' => [
                'email' => $apiKey,
                'password' => $secretKey
            ],
            'response_status' => $response->status(),
            'response_body' => $response->json(),
            'response_raw' => $response->body()
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'] ?? '';
        }
        throw new \Exception('Failed to authenticate with Direpay API: ' . $response->body());
    }

    //withdrawal {PAYOUT}
    public function createWithdrawal(Request $request, $accId)
    {
        $validator = Validator::make($request->all(), [
            'amount'               => 'required|numeric|min:0.01',
            'currency'             => 'required|string|size:3',
            'customer_uid'         => 'required|string',
            'bank_code'            => 'nullable|string|max:50',
            'bank_account_name'    => 'required|string|max:100',
            'bank_account_name_th' => 'nullable|required_if:currency,THB,thb|string|max:100',
            'bank_account_number'  => 'required|string|max:50',
            'bank_name'            => 'required|string|max:100',
            'branch_code'          => 'nullable|string|max:50',
            'txid'                 => 'nullable|string|max:100', //payment_id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $account = Direpay::where('accountId',$accId)->first();
        // $trxn = Transaction::where('payment_id',$request->txid)->where('status','p17')->first();
        // if(!isset($trxn)){
        //     return response()->json(["success"=>false , "message" => "transaction not found"],404);
        // }

        $data = [
            'command'             => 'fiat_withdrawal',
            'hashCode'            => md5('fiat_withdrawal' . $account->direpay_api_secret),
            'callback_url'        => 'https://payment.ryzen-pay.com/api/p17/notification',
            'currency'            => strtoupper($request->input('currency')),
            'customer_uid'        => $request->input('customer_uid'),
            'amount'              => (string) $request->input('amount'),
            'bank_code'           => $request->input('bank_code'),
            'bank_account_name'   => $request->input('bank_account_name'),
            'bank_account_name_th'=> $request->input('bank_account_name_th'),
            'bank_account_number' => $request->input('bank_account_number'),
            'bank_name'           => $request->input('bank_name'),
            'branch_code'         => $request->input('branch_code'),
            'txid'                => $request->input('txid'),
        ];

        // Do not send empty optional fields.
        $data = array_filter($data, function ($value) {
            return $value !== '' && $value !== null && $value !== false;
        });

        $data['signature'] = $this->generatePayoutSignature($data,$account->direpay_api_secret);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getBearerToken($account->direpay_api_email, $account->direpay_api_password),
                'Content-Type'  => 'application/json',
            ])->post($this->baseURL. '/api/callBack', $data);

            Log::channel('direpay')->info('Direpay Withdrawal Request', [
                'request_data'    => $data,
                'response_status' => $response->status(),
                'response_body'   => $response->body(),
            ]);

            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'data'   => $response->json(),
                ]);
            }

            $responseJson = $response->json();

            return response()->json([
                'status'  => 'error',
                'message' => 'API request failed',
                'data'    => $responseJson,
                'raw'     => $responseJson === null ? $response->body() : null,
            ], $response->status());

        } catch (\Exception $e) {
            Log::channel('direpay')->error('Direpay Withdrawal Error', [
                'error' => $e->getMessage(),
                'data'  => $data,
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Internal server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function generatePayoutSignature(array $data, $secret): string
    {
        $filteredData = $data;
        unset($filteredData['signature']);

        $filteredData = array_filter($filteredData, function ($value) {
            return $value !== '' && $value !== null && $value !== false;
        });

        ksort($filteredData);
        $queryString = '';

        foreach ($filteredData as $key => $value) {
            if ($queryString !== '') {
                $queryString .= '&';
            }
            $queryString .= $key . '=' . $value;
        }

        $queryString .= '&secret=' . $secret;

        return strtolower(md5($queryString));
    }
}
