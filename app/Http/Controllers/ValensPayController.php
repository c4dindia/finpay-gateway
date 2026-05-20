<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\ValensPay;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ValensPayController extends Controller
{
    // protected $baseUrl = 'https://valenspayapi-uat.valenspay.com';  //sandbox
    protected $baseUrl = 'https://valenspayapi.valenspay.com'; //production

    //Helper Functions
    protected function generateSignature(string $method,string $secret, string $path, ?array $body = null): array
    {
        $timestamp = time();

        $bodyString = ($body && strtoupper($method) !== 'GET') ? json_encode($body) : '';
        $stringToSign = strtoupper($method) . $timestamp . $path . $bodyString;
        $signature = hash_hmac('sha1', $stringToSign, $secret);
        return [
            'timestamp' => $timestamp,
            'signature' => $signature
        ];
    }

    // create customer
    public function create(Request $request, $accId)
    {

        $validator = Validator::make($request->all(), [
            'firstName'             => 'required|string|max:255',
            'middleName'            => 'nullable|string|max:255',
            'lastName'              => 'required|string|max:255',
            'phoneNumber'           => 'required|string|max:20',
            'gender'                => 'required|string|in:Male,Female,Other',
            'dateOfBirth'           => 'required|date_format:Y-m-d|before:today',
            'email'                 => 'required|email|max:255',
            'countryOfResidence'    => 'required|string|size:2',
            'address'               => 'required|array',
            'address.addressDetail' => 'required|string|max:255',
            'address.city'          => 'required|string|max:255',
            'address.zipCode'       => 'required|string|max:20',
            'address.countyState'   => 'nullable|string|max:255',
        ], [
            'firstName.max' => 'First name may not exceed 255 characters.',
            // Middle Name
            'middleName.max' => 'Middle name may not exceed 255 characters.',
            // Last Name
            'lastName.max' => 'Last name may not exceed 255 characters.',
            // Phone
            'phoneNumber.max' => 'Phone number may not exceed 20 characters.',
            // Gender
            'gender.in' => 'Gender must be Male, Female, or Other.',
            // Date of Birth
            'dateOfBirth.date_format' => 'Date of birth must be in YYYY-MM-DD format.',
            'dateOfBirth.before' => 'Date of birth must be a valid date before today.',
            // Email
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email address may not exceed 255 characters.',
            // Country
            'countryOfResidence.size' => 'Country of residence must be a 2-letter country code (ISO format).',
            // Address Array
            'address.array' => 'Address must be a valid object.',
            // Address Details
            'address.addressDetail.max' => 'Address detail may not exceed 255 characters.',
            'address.city.required' => 'City is required.',
            'address.city.max' => 'City may not exceed 255 characters.',
            'address.zipCode.required' => 'Zip code is required.',
            'address.zipCode.max' => 'Zip code may not exceed 20 characters.',
            'address.countyState.max' => 'County/State may not exceed 255 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customerData = [
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'phoneNumber' => $request->input('phoneNumber'),
            'gender' => $request->input('gender', 'Male'),
            'dateOfBirth' => $request->input('dateOfBirth'),
            'email' => $request->input('email'),
            'countryOfResidence' => $request->input('countryOfResidence'),
            'address' => [
                'addressDetail' => $request->input('address.addressDetail'),
                'city' => $request->input('address.city'),
                'zipCode' => $request->input('address.zipCode'),
            ],
        ];

        if($request->filled('middleName')){
            $customerData['middleName'] = $request->middleName;
        }
        if ($request->filled('address.countyState')) {
            $customerData['address']['countyState'] = data_get($request->all(), 'address.countyState');
        }

        $pmUser = ValensPay::where('accountId',$accId)->where('status','1')->first();
        if(! isset($pmUser)){
            return response()->json([
                "success" => false,
                "message" => "Account not found or not activated"
            ]);
        }

        $path = '/api/v3.2/payment-gateway-3/create-customer';
        $signatureData = $this->generateSignature('POST',$pmUser->valenspay_secret, $path, $customerData);

        $headers = [
            'V-CLIENT-KEY' => $pmUser->valenspay_client_key,
            'timestamp' => $signatureData['timestamp'],
            'signature' => $signatureData['signature'],
            'Accept' => 'text/plain; v-api-version=3.2',
            'Content-Type' => 'application/json; v-api-version=3.2',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->post($this->baseUrl . $path, $customerData);

            $responseBody = $response->json();
            Log::channel('valenspay')->info("create customer api response: ". json_encode($responseBody));

            if ($response->successful()) {
                $data = $responseBody['data'];

                if (!isset($data['customerId'])) {
                    return response()->json([
                        'success' => false,
                        'error' => 'customerId missing in response',
                        'raw_response' => $responseBody
                    ], 500);
                }

                return response()->json([
                    'success' => true,
                    'customer_id' => $data['customerId']
                ], 200);
            }

            return response()->json([
                'success' => false,
                'timestamp' => $signatureData['timestamp'] ?? null,
                'error' => $responseBody ?? $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            Log::channel('valenspay')->error('Valens Pay API Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // create checkout
    public function requestTransfer(Request $request, $accId, $customerId)
    {
        $path = '/api/v3.2/payment-gateway-3/' . $customerId . '/request-transfer';

        $pmUser = ValensPay::where('accountId',$accId)->where('status','1')->first();
        if(! isset($pmUser)){
            return response()->json([
                "success" => false,
                "message" => "Account not found or not activated"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'description' => 'required|string|max:255',
            'returnUrl' => 'nullable|url|max:255',
            // 'paymentMethod' => 'nullable|string|max:50',
            // 'merchantCode' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

         do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $transferData = [
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency', 'USD'),
            'description' => $request->input('description'),
            'returnUrl' => $request->input('returnUrl', $pmUser->redirect_url),
            'correlationId' => $uuid, //checkout_id
            'paymentMethod' => $request->input('paymentMethod', 'VISA_MC'),
            'toCrypto' => 'USDC',
            'lang' =>'en',
            // 'merchantCode' => $request->input('merchantCode'),
        ];

        $signatureData = $this->generateSignature('POST',$pmUser->valenspay_secret, $path, $transferData);

        $headers = [
            'V-CLIENT-KEY' => $pmUser->valenspay_client_key,
            'timestamp' => $signatureData['timestamp'],
            'signature' => $signatureData['signature'],
            'Accept' => 'text/plain; v-api-version=3.2',
            'Content-Type' => 'application/json; v-api-version=3.2',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->post($this->baseUrl . $path, $transferData);
            $responseBody = $response->json();

            if ($response->successful()) {

                $orderId      = $responseBody['data']['orderId'] ?? null;
                $customerId   = $responseBody['data']['customerId'] ?? null;
                $amount       = $responseBody['data']['amount'] ?? null;
                $currency     = $responseBody['data']['currency'] ?? null;
                $status       = $responseBody['data']['status'] ?? null;
                $paymentUrl   = $responseBody['data']['paymentUrl'] ?? null;
                $correlationId= $responseBody['data']['correlationId'] ?? null;

                $trxn               = Transaction::where('checkout_id', $correlationId)->where('status', 'p19')->first() ?: new Transaction();
                $trxn->account_id   = $pmUser->accountId;
                $trxn->amount       = $amount;
                $trxn->currency     = $currency;
                $trxn->from_amount  = $amount;
                $trxn->from_currency = $currency;
                $trxn->checkout_id  = $correlationId;
                $trxn->payment_id   = $orderId;
                $trxn->description  = 'request initiated';
                $trxn->customer_details = 'CustomerId: '. $customerId;
                $trxn->payment_status= ucfirst(strtolower($status));
                $trxn->status       = 'p19';
                $trxn->save();

                return response()->json([
                    'success' => true,
                    'amount' => $amount,
                    'currency' => $currency,
                    'checkout_id' => $correlationId,
                    'customerId' => $customerId,
                    'payment_id' => $orderId,
                    'payment_status' => ucfirst(strtolower($status)),
                    'paymentUrl' => $paymentUrl
                ], 200);
            }

            return response()->json([
                'success' => false,
                'error' => $responseBody
            ], $response->status());

        } catch (\Exception $e) {

            Log::channel('valenspay')->error(
                'Valens Pay Request Transfer API Exception',
                ['message' => $e->getMessage()]
            );

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTransactionStatus($accId, $customerId, $payment_id)
    {
        $pmUser = ValensPay::where('accountId',$accId)->where('status','1')->first();
        if(! isset($pmUser)){
            return response()->json([
                "success" => false,
                "message" => "Account not found or not activated"
            ]);
        }

        $path = '/api/v3.2/payment-gateway-3/' . $customerId . '/payment-transaction-detail/' . $payment_id;
        $signatureData = $this->generateSignature('GET',$pmUser->valenspay_secret, $path);

        $headers = [
            'V-CLIENT-KEY' => $pmUser->valenspay_client_key,
            'timestamp' => $signatureData['timestamp'],
            'signature' => $signatureData['signature'],
            'Accept' => 'text/plain; v-api-version=3.2',
        ];

        try {
            $response = Http::withHeaders($headers)->timeout(30)->get($this->baseUrl . $path);
            $responseBody = $response->json();

            if ($response->successful()) {

                $orderId      = $responseBody['data']['orderId'] ?? null;
                $customerId   = $responseBody['data']['customerId'] ?? null;
                $amount       = $responseBody['data']['amount'] ?? null;
                $currency     = $responseBody['data']['currency'] ?? null;
                $status       = $responseBody['data']['status'] ?? null;
                $correlationId= $responseBody['data']['correlationId'] ?? null;

                $trxn                 = Transaction::where('checkout_id', $correlationId)->where('status', 'p19')->first() ?: new Transaction();
                $trxn->account_id     = $pmUser->accountId;
                // $trxn->amount         = $amount;
                // $trxn->currency       = $currency;
                // $trxn->from_amount    = $amount;
                // $trxn->from_currency  = $currency;
                $trxn->checkout_id    = $correlationId;
                $trxn->payment_id     = $orderId;
                $trxn->description    = $responseBody['data']['description'] ?? $trxn->description  ?? ucfirst(strtolower($status));
                $trxn->customer_details = 'CustomerId: '. $customerId;
                $trxn->payment_status = ucfirst(strtolower($status));
                $trxn->status         = 'p19';
                $trxn->save();

                return response()->json([
                    'success' => true,
                    'amount' => $amount,
                    'currency' => $currency,
                    'checkout_id' => $correlationId,
                    'customerId' => $customerId,
                    'payment_id' => $orderId,
                    'payment_status' => ucfirst(strtolower($status)),
                    // 'data' => $responseBody['data']
                ], 200);
            }

            return response()->json([
                'success' => false,
                'error' => $responseBody
            ], $response->status());

        } catch (\Exception $e) {

            Log::channel('valenspay')->error(
                'Valens Pay getPayment Status API Exception',
                ['message' => $e->getMessage()]
            );

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePaymentLogo(Request $request, $accId)
    {
        $pmUser = ValensPay::where('accountId',$accId)->where('status','1')->first();
        if(! isset($pmUser)){
            return response()->json([
                "success" => false,
                "message" => "Account not found or not activated"
            ]);
        }

        $logoData = [
            'description' => $request->input('description'),
            'logo' => [
                'type' => $request->input('logo.type'),
                'name' => $request->input('logo.name'),
                'file' => $request->input('logo.file'),
            ]
        ];

        $path = '/api/v3.2/partner-setting/payment-logo';
        $signatureData = $this->generateSignature('PUT',$pmUser->valenspay_secret, $path, $logoData);

        $headers = [
            'V-CLIENT-KEY' => $pmUser->valenspay_client_key,
            'timestamp' => $signatureData['timestamp'],
            'signature' => $signatureData['signature'],
            'Accept' => 'text/plain; v-api-version=3.2',
            'Content-Type' => 'application/json; v-api-version=3.2',
        ];

        try {
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->put($this->baseUrl . $path, $logoData);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ], 200);
            }

            Log::channel('valenspay')->error('Valens Pay Update Payment Logo API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'request' => [
                    'url' => $this->baseUrl . $path,
                    'headers' => $headers,
                    'body' => $logoData,
                ],
            ]);

            return response()->json([
                'success' => false,
                'signature' => $signatureData['signature'],
                'timestamp' => $signatureData['timestamp'],
                'error' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::channel('valenspay')->error('Valens Pay Update Payment Logo API Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function findPaymentTransactions(Request $request, $accId, $customerId)
    {
        $pmUser = ValensPay::where('accountId',$accId)->where('status','1')->first();
        if(! isset($pmUser)){
            return response()->json([
                "success" => false,
                "message" => "Account not found or not activated"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'offset' => 'nullable|integer|min:0',
            'search' => 'nullable|string|max:255',
            'filter_fromAmount' => 'nullable|numeric|min:0',
            'filter_toAmount' => 'nullable|numeric|min:0',
            'filter_fromDate' => 'nullable|date',
            'filter_toDate' => 'nullable|date|after_or_equal:filter_fromDate',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $limit = (int) ($validated['limit'] ?? 10);
        $pageProvided = array_key_exists('page', $validated);
        $offsetProvided = array_key_exists('offset', $validated);
        $page = (int) ($validated['page'] ?? 1);
        $offset = $offsetProvided
            ? (int) $validated['offset']
            : ($page - 1) * $limit;

        $transactionData = [
            'limit' => $limit,
        ];
        if ($pageProvided || !$offsetProvided) {
            $transactionData['page'] = $page;
        }
        $transactionData['offset'] = $offset;

        if (!empty($validated['search'])) {
            $transactionData['search'] = $validated['search'];
        }
        if (array_key_exists('filter_fromAmount', $validated)) {
            $transactionData['filter_fromAmount'] = $validated['filter_fromAmount'];
        }
        if (array_key_exists('filter_toAmount', $validated)) {
            $transactionData['filter_toAmount'] = $validated['filter_toAmount'];
        }
        if (!empty($validated['filter_fromDate'])) {
            $transactionData['filter_fromDate'] = $validated['filter_fromDate'];
        }
        if (!empty($validated['filter_toDate'])) {
            $transactionData['filter_toDate'] = $validated['filter_toDate'];
        }

        $path = '/api/v3.2/payment-gateway-3/' . $customerId . '/find-payment-transactions';
        $signatureData = $this->generateSignature('POST',$pmUser->valenspay_secret, $path, $transactionData);

        $headers = [
            'V-CLIENT-KEY' => $pmUser->valenspay_client_key,
            'timestamp' => $signatureData['timestamp'],
            'signature' => $signatureData['signature'],
            'Accept' => 'text/plain; v-api-version=3.2',
            'Content-Type' => 'application/json; v-api-version=3.2',
        ];

        try {
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->post($this->baseUrl . $path, $transactionData);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ], 200);
            }

            Log::channel('valenspay')->error('Valens Pay Find Payment Transactions API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'request' => [
                    'url' => $this->baseUrl . $path,
                    'headers' => $headers,
                    'body' => $transactionData,
                ],
            ]);

            return response()->json([
                'success' => false,
                'signature' => $signatureData['signature'],
                'timestamp' => $signatureData['timestamp'],
                'error' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            Log::channel('valenspay')->error('Valens Pay Find Payment Transactions API Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //WEBHOOK
    public function handleNotification(Request $request)
    {
        Log::channel('valenspay')->info('Webhook received', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'payload' => json_decode($request->getContent(), true),
        ]);

        $description = $request['reason'] ?? $request['status'] ?? null;
        $trxn = Transaction::where('checkout_id', $request['correlationId'])->where('status', 'p19')->first() ?: new Transaction();

        // $trxn->account_id = ;
        $trxn->currency = $request['currency'];
        $trxn->amount = $request['paymentAmount'] ?? $request['amount'];
        $trxn->from_currency = $request['currency'];
        $trxn->from_amount = $request['amount'];
        $trxn->checkout_id = $request['correlationId'];
        $trxn->payment_id = $request['targetId'];
        $trxn->payment_status = ucfirst(strtolower($request['status']));
        $trxn->description = $description;
        $trxn->status = 'p19';
        $trxn->save();

        if($trxn->account_id != null)
        {
            $account = ValensPay::where('accountId', $trxn->account_id)->first();

            try {
                if ($account && $account->redirect_url && $account->b_token) {
                    $headers = [
                        'Content-Type'  => 'application/json',
                        'Authorization' => $account->b_token,
                    ];

                    $webhook = new Client();
                    $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p19/' . $trxn->checkout_id, [
                        'headers' => $headers,
                        'timeout' => 15,
                    ]);
                    Log::channel('valenspay')->info("P19 forward OK response from client, status: {$resp->getStatusCode()}");
                } else {
                    Log::channel('valenspay')->info(" missing Valens Pay details.");
                }
            } catch (RequestException $e) {
                Log::channel('valenspay')->warning("Downstream forward failed: " . $e->getMessage());
            } catch(\Exception $e){
                Log::channel('valenspay')->warning("Exception while sending our client webhook notification: " . $e->getMessage());
            }
        }

        return response()->json(["success" => true, "transaction" => $trxn],200);
    }
}
