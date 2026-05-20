<?php

namespace App\Http\Controllers;

use App\Models\PEighteenPaymentMethod;
use App\Models\Transaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KeynexPayController extends Controller
{

    // protected $apiUrl = "https://engine-sandbox.keynexpay.com"; //sandbox
    protected $apiUrl = "https://engine.keynexpay.com"; //production

    public function createCheckout(Request $request, $accId)
    {
        $checkacc = PEighteenPaymentMethod::where('accountId', $accId)->where('status', '1')->first();

        if (!isset($checkacc)) {
            return response()->json(['error' => 'Unauthorized Account'], 401);
        }

        $apiKey = $checkacc->keynexpay_api_key ?? null;
        if($apiKey == null){
            return response()->json(['error' => 'MID not assigned yet'], 401);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string',
            'description' => 'required|string|max:512',
            'returnUrl' => 'nullable|string|url',

            // 'cardholderName' => 'required|string',
            // 'cardNumber' => 'required|string|digits_between:13,19',
            // 'expiryMonth' => 'required|string|digits:2',
            // 'expiryYear' => 'required|string|digits:4',
            // 'cardSecurityCode' => 'required|string|digits_between:3,4',

            'firstName' => 'required|string',
            'lastName'  => 'required|string',
            'email'     => 'required|email',
            'phone'     => 'required|string',
            // 'dateOfBirth'            => 'required|date',
            'citizenshipCountryCode' => 'required|string|max:2',
        ]);

        do {
            $uuid = Str::uuid()->toString();
        } while (Transaction::where('checkout_id', $uuid)->exists());

        $requestBody = [
            'referenceId' => $uuid,
            'paymentType' => 'DEPOSIT',
            'paymentMethod' => 'BASIC_CARD',
            'amount' => $request->amount,
            'currency' => $request->currency,
            'description' => $request->description,
            'returnUrl' => $request->returnUrl ?? 'www.ryzen-pay.com',
            'webhookUrl' =>  'https://payment.ryzen-pay.com/api/p18/notification',
            'card' => [
                'cardNumber' => $request->cardNumber,
                'cardholderName' => $request->cardholderName,
                'expiryMonth' => $request->expiryMonth,
                'expiryYear' => $request->expiryYear,
                'cardSecurityCode' => $request->cardSecurityCode,
            ],
            'customer' => [
                "firstName"   => $request->firstName,
                "lastName"    => $request->lastName,
                "email"       => $request->email,
                "phone"       => $request->phone,
                // "dateOfBirth" => $request->dateOfBirth,
                // "locale"      => "en",
                // "citizenshipCountryCode" => $request->citizenshipCountryCode,
                // "ip"          => $request->ip(),
                // "streetLine1" => $request->streetLine1,
                // "city"        => $request->city,
                // "country"     => $request->citizenshipCountryCode,
            ],
            "billingAddress" => [
                "addressLine1" => $request->streetLine1,
                "city" => $request->city,
                "state" => $request->stateProvince ?? null,
                "countryCode" => $request->citizenshipCountryCode,
                "postalCode" => $request->postalCode,
            ]
            // "firstname" => $request->firstName,
            // "surname" =>  $request->lastName,
            // "streetLine1" => $request->streetLine1,
            // "streetLine2" =>  "",
            // "city" =>  $request->city,
            // "postalCode" =>  $request->postalCode,
            // "stateProvince" =>  $request->stateProvince ?? null,
            // "country" =>  $request->citizenshipCountryCode,
        ];

        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];
           $headers['Authorization'] = 'Bearer ' . $apiKey;
           $response = Http::withHeaders($headers)->post($this->apiUrl . '/api/v1/payments', $requestBody);

            // Log the request for debugging
            Log::channel('keynexpay')->info('KeynexPay createPayment request', [
                'response_body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseJson = (array) $response->json();
                $result = $responseJson['result'] ?? [];
                $paylink = $result['redirectUrl'] ?? null;

                $trxn = Transaction::where('checkout_id', $uuid)->where('status', 'p18')->first() ?: new Transaction();
                $trxn->account_id = $checkacc->accountId;
                $trxn->amount = $result['amount'];
                $trxn->currency = $result['currency'];
                $trxn->from_amount =  $request->amount;
                $trxn->from_currency = $request->currency;
                $trxn->checkout_id = $uuid;
                $trxn->payment_id = $result['id'];
                $trxn->payment_status = ucfirst(strtolower($result['state']));
                $trxn->description = $request->description ?? 'N/A';
                $trxn->customer_details = 'Name: '. $request->cardholderName ;
                $trxn->status = 'p18';
                $trxn->save();

                return response()->json([
                    'success'     => true,
                    'amount'      => number_format((float)$trxn->amount, 2, '.', ''),
                    'currency'    => $trxn->currency,
                    'checkout_id' => $uuid,
                    'payment_id'  => $trxn->payment_id,
                    'status'      => $trxn->payment_status,
                    'link'        => $paylink,
                ]);

            } else {
                Log::channel('keynexpay')->error('KeynexPay createPayment error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment with KeynexPay',
                    'error' => $response->json()
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::channel('keynexpay')->error('KeynexPay createPayment exception', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating payment'
            ], 500);
        }
    }

    public function getTransactionStatus($accId, $checkout_id)
    {
        $checkaccId = PEighteenPaymentMethod::where('accountId', $accId)->where('status', '1')->first();
        if ($checkaccId == null) {
            return response()->json(['message' => 'Unauthorized Account Id'], 401);
        }
        $transaction = Transaction::where('checkout_id', $checkout_id)->where('account_id', $accId)->where('status', 'p18')->first();
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

    public function updateP18TrxnStatus($trxn_id)
    {
        $trxn = Transaction::where('payment_id',$trxn_id)->first();
        $account = PEighteenPaymentMethod::where('accountId',$trxn->account_id)->first();

        // Get API configuration
        $apiUrl = $this->apiUrl;
        $apiKey = $account->keynexpay_api_key;

        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' =>'Bearer ' . $apiKey
            ];

            $response = Http::withHeaders($headers)->get($apiUrl . '/api/v1/payments/' . $trxn_id);

            Log::channel('keynexpay')->info('KeynexPay get transaction request', [
                'response_body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseJson = (array) $response->json();
                $result = $responseJson['result'] ?? [];

                $trxn->payment_status = ucfirst(strtolower($result['state']));
                if(isset($result['errorMessage'])){
                    $trxn->description = $result['errorMessage'];
                }
                if(isset($result['externalResultCode'])){
                    $trxn->description = $result['externalResultCode'];
                }
                $trxn->save();

                return back()->with("success", "Status Updated!");

            } else {
                Log::channel('keynexpay')->error('KeynexPay findPayment error', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return back()->with("error","Failed to find payment with Provider");
            }

        } catch (\Exception $e) {
            Log::channel('keynexpay')->error('KeynexPay findPayment exception', [
                'error' => $e->getMessage()
            ]);

            return back()->with("error","An error occurred while finding payment");
        }
    }

    public function handleNotification(Request $request)
    {
        Log::channel('keynexpay')->info("Webhook Request Data: ", $request->all());

        $paymentMethodDetails = $request->input('paymentMethodDetails', []);
        $customer = $request->input('customer', []);

        $description = $request['paymentType'] . ': ' . $request['state'];

        $firstName = $request->input('customer.firstName');
        $lastName  = $request->input('customer.lastName');
        $email     = $request->input('customer.email');

        $trxn = Transaction::where('checkout_id', $request['referenceId'])->where('status', 'p18')->first() ?: new Transaction();

        if(isset($request['errorMessage'])){
            $description = $request['errorMessage'];
        }
        if(isset($request['externalResultCode'])){
            $description = $request['externalResultCode'];
        }

        // $trxn->account_id = ;
        $trxn->currency = $request['currency'];
        $trxn->amount = $request['amount'];
        $trxn->from_currency = $request['currency'];
        $trxn->from_amount = $request['amount'];
        $trxn->checkout_id = $request['referenceId'];
        $trxn->payment_id = $request['id'];
        $trxn->payment_status = ucfirst(strtolower($request['state']));
        $trxn->description = $description;
        $trxn->customer_details = trim("Name: " . ($firstName ?? '') . " " . ($lastName ?? '') .  ", Email: " . ($email ?? '') );
        $trxn->card_number = $paymentMethodDetails['customerAccountNumber'] ?? null;
        $trxn->status = 'p18';
        $trxn->save();

        Log::channel('keynexpay')->info("Transaction Updated from Webhook");

        if($trxn->account_id != null)
        {
            $account = PEighteenPaymentMethod::where('accountId', $trxn->account_id)->first();
            $shopSigningKey = $account->keynexpay_secret_key;
            $receivedSignature = $request->header('Signature');
            $rawBody = $request->getContent();
            $calculatedSignature = hash_hmac('sha256', $rawBody, $shopSigningKey);
            if (!$receivedSignature || !hash_equals($calculatedSignature, $receivedSignature)) {
                Log::channel('keynexpay')->warning('Invalid webhook signature', [
                    'received' => $receivedSignature,
                    'calculated' => $calculatedSignature
                ]);
            } else {
                Log::channel('keynexpay')->info('Valid webhook signature');
            }

            try {
                if ($account && $account->redirect_url && $account->b_token) {
                    $headers = [
                        'Content-Type'  => 'application/json',
                        'Authorization' => $account->b_token,
                    ];

                    $webhook = new Client();
                    $resp = $webhook->get($account->redirect_url . '/api/RyzenPay/p18/' . $trxn->checkout_id, [
                        'headers' => $headers,
                        'timeout' => 15,
                    ]);
                    Log::channel('keynexpay')->info("P18 forward OK response from client, status: {$resp->getStatusCode()}");
                } else {
                    Log::channel('keynexpay')->info(" missing PEighteenPaymentMethod details.");
                }
            } catch (RequestException $e) {
                Log::channel('keynexpay')->warning("Downstream forward failed: " . $e->getMessage());
            } catch(\Exception $e){
                Log::channel('keynexpay')->warning("Exception while sending our client webhook notification: " . $e->getMessage());
            }
        }

        return response()->json([
            "success" => true,
        ],200);
    }
}
