<?php

namespace App\Http\Controllers\Inabit;

use App\Http\Controllers\Controller;
use App\Services\InabitWalletInfoService;
use App\Services\InabitApiWalletService;
use App\Services\InabitGraphQLClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InabitDebugController extends Controller
{
    public function __construct(
        protected InabitWalletInfoService $walletInfo,
        protected InabitApiWalletService $apiWallets,
        protected InabitGraphQLClient $client
    ) {}

    public function wallets()
    {
        $wallets = $this->walletInfo->getOrganizationWallets();

        return response()->json($wallets);
    }

    public function createApiWallet(Request $request)
    {
        $blockchainId     = $request->input('blockchain_id');     // optional
        $financialAssetId = $request->input('financial_asset_id'); // optional
        $organizationId   = $request->input('organization_id');   // optional

        return response()->json(["message" => "Currently not making API Wallets"]);

        // $result = $this->apiWallets->createApiWalletAddress(
        //     $blockchainId,
        //     $financialAssetId,
        //     $organizationId
        // );

        // return response()->json([
        //     'success' => true,
        //     'data'    => $result,
        // ]);
    }

    public function registerCustomerUuidByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {

            $client = new Client([
                'base_uri' => 'https://api.inabit.biz/v1/',
                'timeout'  => 30,
            ]);

            $response = $client->post('customer', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer 417003e112cebc4e599f101313cf4a15225145348a71706386e53b8c3137a2ce',
                ],
                'json' => [
                    'customerIdentifier' => $request->email,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            Log::info("Register Customer UUID Response", [
                'email' => $request->email,
                'customer_uuid' => $body['data']['id'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data'    => $body['data'] ?? null,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateAddressToken(Request $request)
    {
        $request->validate([
            'customerUuid' => 'required|string',
        ]);

        try {
            $client = new Client([
                'base_uri' => 'https://api.inabit.biz/v1/',
                'timeout'  => 60,
            ]);

            $response = $client->post('customer/address-token', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer 417003e112cebc4e599f101313cf4a15225145348a71706386e53b8c3137a2ce',
                ],
                'json' => [
                    'customerUuid' => $request->customerUuid,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // ⭐ Best-practice structured log
            Log::info("Address Token Generated", [
                'customerUuid' => $request->customerUuid,
                'api_response' => $body
            ]);

            return response()->json([
                'success' => true,
                'data'    => $body['data'] ?? null,
            ]);

        } catch (\Exception $e) {

            Log::error("Error Generating Address Token", [
                'customerUuid' => $request->customerUuid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function showWidgetPage(Request $request)
    {
        // tokenId should come from previous API step
        // for testing, pass ?token=xxxx in URL

        $tokenId = $request->query('token');

        return view('payment.inabit.widget', compact('tokenId'));
    }

    public function getInabitTransaction($transactionId)
    {
        $query = <<<'GRAPHQL'
            query Transaction($where: TransactionWhereUniqueInput!) {
              transaction(where: $where) {
                id
                amount
                createdAt
                fee
                note
                isAccelerated
                priority
                rateUSD
                rateEUR
                kyt

                blockchain {
                  name
                  code
                }

                createdBy {
                  fullName
                }

                status {
                  status
                }

                wallet {
                  name
                  id
                }

                financialAsset {
                  code
                }
              }
            }
        GRAPHQL;

        $variables = [
            "where" => [
                "id" => $transactionId
            ]
        ];

        try {
            $data = $this->client->request($query, $variables, 'admin');
            Log::info("Inabit Trxn Details:", (array)$data);

            return [
                "success" => true,
                "data" => $data
            ];

        } catch (RequestException $e) {

            Log::error("Inabit Transaction Lookup Failed", [
                "transactionId" => $transactionId,
                "error"         => $e->getMessage(),
                "response"      => $e->hasResponse()
                                    ? $e->getResponse()->getBody()->getContents()
                                    : null
            ]);

            return [
                "success" => false,
                "message" => "Request to Inabit failed",
                "error"   => $e->getMessage()
            ];

        } catch (\Exception $e) {

            Log::error("Inabit Transaction Lookup Exception", [
                "transactionId" => $transactionId,
                "error" => $e->getMessage()
            ]);

            return [
                "success" => false,
                "message" => "Unexpected error occurred",
                "error"   => $e->getMessage()
            ];
        }
    }

}
