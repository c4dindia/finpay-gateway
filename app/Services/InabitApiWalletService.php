<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class InabitApiWalletService
{
    public function __construct(
        protected InabitGraphQLClient $client
    ) {
    }

    /**
     * Create a new inabit API wallet + address for a customer.
     *
     * @param  string|null $blockchainId
     * @param  string|null $financialAssetId
     * @param  string|null $organizationId
     * @return array  ['address' => ..., 'walletId' => ...]
     */
    public function createApiWalletAddress(
        ?string $blockchainId = null,
        ?string $financialAssetId = null,
        ?string $organizationId = null
    ): array {
        $blockchainId     = $blockchainId     ?: config('services.inabit.blockchain_id');
        $financialAssetId = $financialAssetId ?: config('services.inabit.asset_id');
        $organizationId   = $organizationId   ?: config('services.inabit.organization_id');

        if (! $blockchainId || ! $financialAssetId || ! $organizationId) {
            throw new \RuntimeException('Missing blockchain_id / asset_id / organization_id for CreateApiWalletAddress');
        }

        $mutation = <<<'GRAPHQL'
mutation CreateApiWalletAddress($data: ApiWalletCreateAddressInput!) {
  createApiWalletAddress(data: $data) {
    address
    walletId
  }
}
GRAPHQL;

        $variables = [
            'data' => [
                'blockchainId'     => $blockchainId,
                'financialAssetId' => $financialAssetId,
                'organizationId'   => $organizationId,
            ],
        ];

        // Log request
        Log::info('Inabit CreateApiWalletAddress request', [
            'variables' => $variables,
        ]);

        try {
            $data = $this->client->request($mutation, $variables, 'admin');

            // Log raw GraphQL data
            Log::info('Inabit CreateApiWalletAddress response', [
                'data' => $data,
            ]);

            if (! isset($data['createApiWalletAddress'])) {
                throw new \RuntimeException('Inabit returned no createApiWalletAddress field');
            }

            return $data['createApiWalletAddress'];
        } catch (\Throwable $e) {
            Log::error('Inabit CreateApiWalletAddress failed', [
                'message'   => $e->getMessage(),
                'exception' => $e,
            ]);

            throw $e; // or return a structured error if you prefer
        }
    }
}
