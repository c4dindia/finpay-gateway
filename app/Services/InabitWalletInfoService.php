<?php
// app/Services/InabitWalletInfoService.php

namespace App\Services;

class InabitWalletInfoService
{
    public function __construct(
        protected InabitGraphQLClient $client
    ) {
    }

    public function getOrganizationWallets(?string $organizationId = null): array
    {
        $organizationId = $organizationId ?: config('services.inabit.organization_id');

        $query = <<<'GRAPHQL'
query Wallets($where: WalletWhereInput) {
  wallets(where: $where) {
    id
    name
    balanceUSD
    balanceEUR
    walletCryptoAccounts {
      name
      balance
      financialAsset {
        code
      }
      blockchain {
        code
      }
    }
  }
}
GRAPHQL;

        $variables = [
            'where' => [
                'organization' => [
                    'id' => $organizationId,
                ],
            ],
        ];

        $data = $this->client->request($query, $variables, 'admin');

        return $data['wallets'] ?? [];
    }
}
