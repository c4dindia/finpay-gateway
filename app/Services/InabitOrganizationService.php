<?php

namespace App\Services;

class InabitOrganizationService
{
    public function __construct(
        protected InabitGraphQLClient $client
    ) {}

    /**
     * Get all organizations for a given user.
     * If $userId is null, we derive it from the ADMIN login token payload.
     */
    public function getUserOrganizations(?string $userId = null): array
    {
        $userId = $userId ?: $this->getUserIdFromAdminLoginToken();

        if (! $userId) {
            throw new \RuntimeException('Unable to determine Inabit API user ID (check admin login token).');
        }

        $query = <<<'GRAPHQL'
query User($where: UserWhereUniqueInput!) {
  user(where: $where) {
    id
    fullName
    organizations {
      id
      name
      city
      email
      industry
      isIndividual
      unit
      wallets {
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
      zipCode
    }
  }
}
GRAPHQL;

        $variables = [
            'where' => [
                'id' => $userId,
            ],
        ];

        $data = $this->client->request($query, $variables, 'admin');

        $user = $data['user'] ?? null;

        if (! $user) {
            return [];
        }

        // You can also return ['user' => $user] if you want full info
        return $user['organizations'] ?? [];
    }

    /**
     * Helper to get ONE organization by ID (from the list).
     */
    public function getOrganizationById(string $organizationId, ?string $userId = null): ?array
    {
        $orgs = $this->getUserOrganizations($userId);

        foreach ($orgs as $org) {
            if (($org['id'] ?? null) === $organizationId) {
                return $org;
            }
        }

        return null;
    }

    /**
     * Decode the ADMIN login token and extract the user ID from the JWT payload.
     *
     * Assumes your .env has INABIT_LOGIN_TOKEN_ADMIN set.
     */
    protected function getUserIdFromAdminLoginToken(): ?string
    {
        $token = config('services.inabit.login_token_admin');

        if (! $token) {
            return null;
        }

        $parts = explode('.', $token);
        if (count($parts) < 2) {
            return null;
        }

        // JWT payload is base64url encoded JSON
        $payload = $parts[1];

        // Convert base64url -> base64
        $payload = strtr($payload, '-_', '+/');

        $decoded = json_decode(base64_decode($payload), true);

        // Docs say "id: ID of the logged in user"
        return $decoded['id'] ?? null;
    }
}
