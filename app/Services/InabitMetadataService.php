<?php

namespace App\Services;

class InabitMetadataService
{
    public function __construct(
        protected InabitGraphQLClient $client
    ) {}

    /**
     * Fetch a financial asset (e.g. USDT, ETH, BTC) by its code.
     *
     * @param  string $code  e.g. 'USDT', 'ETH', 'BTC'
     * @return array|null    ['code' => 'USDT', 'id' => 'clef...'] or null
     */
    // ($where: FinancialAssetWhereInput)
    // (where: $where)
    public function getFinancialAssetByCode(string $code): ?array
    {
        $query = <<<'GRAPHQL'
query FinancialAssets {
  financialAssets {
    code
    id
  }
}
GRAPHQL;

        $variables = [
            'where' => [
                'code' => [
                    'equals' => $code,
                ],
            ],
        ];

        $data = $this->client->request($query, $variables, 'admin');

        $assets = $data['financialAssets'] ?? [];

        return $assets;
    }

    /**
     * Fetch a blockchain by CODE (preferred) – e.g. 'ethereum', 'bitcoin', 'tron'.
     *
     * @param  string $code
     * @return array|null   ['id' => '...', 'name' => 'Ethereum', 'code' => 'ethereum']
     */
    public function getBlockchainByCode(string $code): ?array
    {
        $query = <<<'GRAPHQL'
query Blockchains($where: BlockchainWhereInput) {
  blockchains(where: $where) {
    id
    name
    code
  }
}
GRAPHQL;

        $variables = [
            'where' => [
                'code' => [
                    'equals' => $code,
                ],
            ],
        ];

        $data = $this->client->request($query, $variables, 'admin');

        $chains = $data['blockchains'] ?? [];

        return $chains[0] ?? null;
    }

    /**
     * Alternative: fetch blockchain by NAME (e.g. 'Bitcoin', 'Ethereum').
     */
    // ($where: BlockchainWhereInput)
    // (where: $where)
    public function getBlockchainByName(string $name): ?array
    {
        $query = <<<'GRAPHQL'
query Blockchains {
  blockchains {
    id
    name
    code
  }
}
GRAPHQL;

        $variables = [
            'where' => [
                'name' => [
                    'equals' => $name,
                ],
            ],
        ];

        $data = $this->client->request($query, $variables, 'admin');

        $chains = $data['blockchains'] ?? [];

        return $chains ?? null;
    }
}
