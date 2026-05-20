<?php

namespace App\Http\Controllers;

use App\Models\PTenPaymentMethod;
use App\Models\Transaction;
use App\Services\InabitApiWalletService;
use App\Services\InabitGraphQLClient;
use App\Services\InabitMetadataService;
use App\Services\InabitOrganizationService;
use App\Services\InabitWalletInfoService;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InabitController extends Controller
{
    public function __construct(
        protected InabitWalletInfoService $walletInfo,
        protected InabitApiWalletService $apiWallets,
        protected InabitGraphQLClient $client,
        protected InabitMetadataService $meta,
        protected InabitOrganizationService $orgs,
    ) {}

    // Sandbox Customer address Widget: RyzenPay TestNet
    // 'Bearer 417003e112cebc4e599f101313cf4a15225145348a71706386e53b8c3137a2ce' widget api token
    // merchant name: Test Widget
    // Sandbox Purchase address Widget: Test Purchase Widget
    // 'Bearer a907b8f22e306d618e1df3c686225e8c2c736c002ff92029dcec32c77a429cda' widget api token
    // merchant name: RyzenPAY
    // PRODUCTION ORGANIZATION API KEY 572091c21e53d0ad2d82014d7a9af74e3989aa34e753525367306f08076f0b7e [RyzenPay Prod]

    public function createCheckout(Request $request , $accId)
    {
        $checkaccId = PTenPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account Id'],401);
        }

        $bearerToken = $checkaccId->inabit_widget_api_key;

        do {
            $random = (string) random_int(100000000000000, 999999999999999);
            $uuid = "ryzen". $random;
        } while (Transaction::where('checkout_id', $uuid)->exists() );

        $client = new Client([
                'base_uri' => 'https://api.inabit.biz/v1/',
                'timeout'  => 45,
            ]);

        try {
            $response = $client->post('customer', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $bearerToken,
                ],
                'json' => [
                    'customerIdentifier' => $uuid,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            $customer_uuid = $body['data']['id'];

            Log::channel('inabit')->info("Register Customer UUID Response", [
                'customerIdentifier' => $uuid,
                'data' => $body['data'] ?? null
            ]);

        } catch (RequestException $e) {

            $statusCode = $e->hasResponse()
                ? $e->getResponse()->getStatusCode()
                : 500;

            $apiMessage = $e->getMessage();

            if ($e->hasResponse()) {
                $body = (string) $e->getResponse()->getBody();
                $decoded = json_decode($body, true);

                // Try to read Inabit-style error JSON
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    if (isset($decoded['error']['messages']) && is_array($decoded['error']['messages'])) {
                        $apiMessage = implode(' ', $decoded['error']['messages']);
                    } elseif (isset($decoded['error']['message'])) {
                        $apiMessage = $decoded['error']['message'];
                    }
                }
            }

            Log::channel('inabit')->error("Error Registering Customer (RequestException)", [
                'error'        => $apiMessage,
                'exception'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $apiMessage,
            ], $statusCode);

        } catch (\Exception $e) {

            Log::channel('inabit')->error("Error Registering Customer", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

        try {
            $response2 = $client->post('customer/address-token', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => $bearerToken,
                ],
                'json' => [
                    'customerUuid' => $customer_uuid,
                ],
            ]);

            $body2 = json_decode($response2->getBody()->getContents(), true);
            Log::channel('inabit')->info("Address Token Generated", [
                'customerUuid' => $customer_uuid,
                'api_response' => $body2
            ]);

            $addressGenerationToken = $body2['data']['addressGenerationToken'] ?? null;

            $trans = new Transaction();

            $trans->account_id     = $checkaccId->accountId;
            $trans->currency       = 'currency';
            $trans->amount         = 0;
            // $trans->net_amount     = $amount;
            // $trans->fees           = $amount;
            // $trans->from_currency  = $fromCurrency;
            // $trans->from_amount    = $fromAmount;
            $trans->checkout_id    = $uuid;
            $trans->payment_id     = $customer_uuid;
            $trans->payment_status = 'Pending';
            $trans->description    = 'Checkout created' ; //customerIdentifier
            // $trans->customer_details    = 'Name: zorro kungen , Email: luinusojat@gmail.com , Phone: +46724030959';
            // $trans->transvoucher_blockchainHashTrxn = $amount;
            $trans->status         = 'p10';

            $trans->save();

            return response()->json([
                "success" => true,
                "checkout_id" => $trans->checkout_id,
                "link"        => url('/payment/p10/payment-page/'. $addressGenerationToken),
            ]);

        } catch (RequestException $e) {

            $statusCode = $e->hasResponse()
                ? $e->getResponse()->getStatusCode()
                : 500;

            $apiMessage = $e->getMessage();

            if ($e->hasResponse()) {
                $body = (string) $e->getResponse()->getBody();
                $decoded = json_decode($body, true);

                // Try to read Inabit-style error JSON
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    if (isset($decoded['error']['messages']) && is_array($decoded['error']['messages'])) {
                        $apiMessage = implode(' ', $decoded['error']['messages']);
                    } elseif (isset($decoded['error']['message'])) {
                        $apiMessage = $decoded['error']['message'];
                    }
                }
            }

            Log::channel('inabit')->error("Error Registering Customer (RequestException)", [
                'error'        => $apiMessage,
                'exception'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $apiMessage,
            ], $statusCode);

        } catch (\Exception $e) {

            Log::channel('inabit')->error("Error Generating Address Token", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getTransactionStatus($accId , $checkout_id)
    {
        $checkaccId = PTenPaymentMethod::where('accountId',$accId)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p10')->first();
        if($transaction == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        return response()->json([
            'data' => [
                "currency" => $transaction->currency,
                "amount" => number_format($transaction->amount,2),
                "checkout_id" => $transaction->checkout_id,
                "payment_id" => $transaction->payment_id,
                "payment_status" => ucfirst($transaction->payment_status),
                "description" => $transaction->description,
                "created_at" => $transaction->created_at,
                "updated_at" => $transaction->updated_at
            ]
        ],200);
    }

    public function showPaymentPage($token)
    {
        $tokenId = $token;
        return view('payment.inabit.widget', compact('tokenId'));
    }

    /// PURCHASE WIDGET ///
    public function createPurchaseCheckout(Request $request, $accId)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string'],
            // 'subTitle'           => ['required', 'string'],
            'siteName'           => ['nullable', 'string'],
            'fiatAmount'         => ['required', 'numeric', 'min:0.01'],
            'fiatCurrency'       => ['required', 'string', 'size:3'],
        ]);

        $checkaccId = PTenPaymentMethod::where('accountId',$accId)->where("inabit_purchase_widget_api_key","!=",null)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['error' => 'Unauthorized Account ID'],401);
        }

        do {
            $random = (string) random_int(100000000000000, 999999999999999);
            $uuid = "ryzen-prchse-". $random;
        } while (Transaction::where('checkout_id', $uuid)->exists() );

        $payload = [
            'title'              => $validated['title'],
            'subTitle'           => "RYZEN-PAY PAYMENT GATEWAY",
            'siteName'           => $validated['siteName'] ?? "www.ryzen-pay.com",
            'purchaseIdentifier' => $uuid,
            'fiatAmount'         => (float) $validated['fiatAmount'],
            'fiatCurrency'       => strtoupper($validated['fiatCurrency']),
        ];

        $baseUrl = 'https://api.inabit.biz/v1';
        $token   = $checkaccId->inabit_purchase_widget_api_key;

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'P-10 PURCHASE service\'s token missing. Set PROVIDER_BEARER_TOKEN',
            ], 500);
        }

        $client = new Client([
            'base_uri' => $baseUrl,
            'timeout'  => 45,
            'verify' => false
        ]);

        try {
            Log::channel("inabit")->info("Purchase Widget create checkout API request: ", $payload);
            $res = $client->post('/v1/purchase', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => $payload,
            ]);

            $status = $res->getStatusCode();
            $body   = (string) $res->getBody();
            $responseData   = json_decode($body, true);

            Log::channel("inabit")->info("Purchase widget api response: ", $responseData);

            if ($status < 200 || $status >= 300) {
                Log::channel('inabit')->warning('Inabit purchase non-2xx', ['status' => $status, 'body' => $body]);

                return response()->json([
                    'message' => 'Purchase create failed',
                    'status'  => $status,
                    'error'   => $responseData ?? $body,
                ], 502);
            }

            $token = data_get($responseData, 'data.id');

            if (!$token) {
                return response()->json([
                    'message' => 'Purchase created but token not found in response',
                    'raw'     => $responseData,
                ], 502);
            }

            $trans = Transaction::where('checkout_id', $uuid)->where('status', 'p10')->first() ?: new Transaction();

            $trans->account_id     = $checkaccId->accountId;
            $trans->currency       = $validated['fiatCurrency'];
            $trans->amount         = (float) $validated['fiatAmount'];
            // $trans->net_amount     = $amount;
            // $trans->fees           = $amount;
            $trans->from_currency  = $validated['fiatCurrency'];
            $trans->from_amount    = (float) $validated['fiatAmount'];
            $trans->checkout_id    = $uuid;
            $trans->payment_id     = $token;
            $trans->payment_status = 'Pending';
            $trans->description    = 'Purchase checkout created' ; //customerIdentifier
            // $trans->customer_details    = 'Name: zorro kungen , Email: luinusojat@gmail.com , Phone: +46724030959';
            // $trans->transvoucher_blockchainHashTrxn = $amount;
            $trans->token          = $token;
            $trans->status         = 'p10';

            $trans->save();

            return response()->json([
                'success' => true,
                'from_amount'  => $validated['fiatAmount'],
                'from_currency'=> $validated['fiatCurrency'],
                'checkout_id'  => $trans->checkout_id, //purchaseIdentifier
                'link'         => url('/payment/p10/purchase/payment-page/'. $token),
                'token'        => $token,
            ]);
        } catch (RequestException $e) {
            $status = optional($e->getResponse())->getStatusCode();
            $body   = optional($e->getResponse())->getBody()?->getContents();

            Log::channel('inabit')->error('Inabit purchase request exception', [
                'status' => $status,
                'error'  => $e->getMessage(),
                'body'   => $body,
            ]);

            return response()->json([
                'success' =>false,
                'message' => 'Service Provider request failed',
                'status'  => $status,
                'error'   => $body ?: $e->getMessage(),
            ], 502);
        } catch (\Throwable $e) {
            Log::channel('inabit')->error('Inabit purchase unexpected exception', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected server error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function showPurchasePaymentPage($token)
    {
        $tokenId = $token;
        return redirect()->away('https://www.inabit.biz/?purchaseId=' . $tokenId);
        // return view('payment.inabit.purchase-widget', compact('tokenId'));
    }

    public function getPurchaseTransactionStatus($accId , $checkout_id)
    {
        $checkaccId = PTenPaymentMethod::where('accountId',$accId)->where("inabit_purchase_widget_api_key","!=",null)->where('status','1')->first();
        if($checkaccId == null){
            return response()->json(['message' => 'Unauthorized Account Id'],401);
        }
        $transaction = Transaction::where('checkout_id',$checkout_id)->where('account_id',$accId)->where('status','p10')->first();
        if($transaction == null){
            return response()->json(['message' => 'Unauthorized Checkout Id or Transaction not completed.'],401);
        }

        return response()->json([
            'data' => [
                "currency"          => $transaction->currency,
                "amount"            => number_format($transaction->amount,2),
                "checkout_id"       => $transaction->checkout_id,
                "payment_id"        => $transaction->payment_id,
                "payment_status"    => ucfirst($transaction->payment_status),
                "description"       => $transaction->description,
                "token"             => $transaction->token,
                "created_at"        => $transaction->created_at
            ]
        ],200);
    }

    //updating trxn status from inabit trxn api
    public function updateP10TrxnStatus($trxn_id)
    {
        $trxn = Transaction::where('status', 'p10')->where('payment_id', $trxn_id)->first();
        $oldStatus = ucfirst( strtolower($trxn->payment_status) );
        $baseUrl = 'https://api.inabit.biz/v1';

        try {
                $client = new Client();
                $checkaccId = PTenPaymentMethod::where('accountId', $trxn->account_id)->first();
                $purchaseWidgetToken = $checkaccId->inabit_purchase_widget_api_key ;
                $response = $client->get($baseUrl . '/widget/purchase/' . $trxn_id, [
                    'headers' => [
                        'Accept'  => 'application/json',
                        'Authorization' => 'Bearer '. $purchaseWidgetToken
                    ]
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);
                Log::channel('inabit')->info("Inabit response for transaction id {$trxn->payment_id}:", $responseBody);

                $data = $responseBody['data'] ?? [];

                if (!empty($data['status'])) {
                    $trxn->payment_status = ucfirst($data['status']);
                    $trxn->save();
                    Log::channel('inabit')->info("Transaction {$trxn->payment_id} status updated to {$trxn->payment_status}");
                } else {
                    Log::channel('inabit')->warning("No status found in response for transaction {$trxn->id}", $responseBody);
                }

            } catch (RequestException $e) {
                $errorBody = $e->getResponse()
                    ? json_decode($e->getResponse()->getBody(), true)
                    : ['error' => $e->getMessage()];

                Log::channel('inabit')->error("Error calling  API for transaction {$trxn->id}: ", (array) $errorBody);
                return back()->with("error","Try again later");
            } catch (\Exception $e) {
                Log::channel('inabit')->error("Unexpected error for transaction {$trxn->id}: " . $e->getMessage());
                return back()->with("error","Try again later.");
            }

            if( $oldStatus != ucfirst( strtolower($trxn->payment_status) ) )
            {
                try {
                    if ($checkaccId && $checkaccId->redirect_url && $checkaccId->b_token) {
                        $headers = [
                            'Content-Type'  => 'application/json',
                            'Authorization' => $checkaccId->b_token,
                        ];
                        $webhook = new Client();
                        $resp = $webhook->get($checkaccId->redirect_url . '/api/RyzenPay/p10/' . $trxn->checkout_id, [
                            'headers' => $headers,
                            'timeout' => 15,
                        ]);
                        Log::info("P10 forward OK response to client, status: {$resp->getStatusCode()}");
                    } else {
                        Log::info(" missing PTenPaymentMethod details.");
                    }
                } catch (RequestException $e) {
                    Log::warning("Downstream forward failed: " . $e->getMessage());
                }
            }

        return back()->with("success","Updated");
    }
}
