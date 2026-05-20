<?php
// app/Services/InabitGraphQLClient.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InabitGraphQLClient
{
    public function __construct(
        protected InabitAuthService $auth
    ) {}

    /**
     * @param string $query     GraphQL query/mutation string
     * @param array  $variables Variables array
     * @param string $userType  'admin' or 'signer'
     */
    public function request(string $query, array $variables = [], string $userType = 'admin'): array
    {
        $accessToken = $this->auth->getAccessToken($userType);
        Log::info("Access Token: ". $accessToken);

        $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$accessToken,
                'Content-Type'  => 'application/json',
            ])
            ->post(config('services.inabit.base_url'), [
                'query'     => $query,
                'variables' => $variables,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException(
                'Inabit API HTTP error: '.$response->status().' '.$response->body()
            );
        }

        $json = $response->json();

        if (isset($json['errors']) && count($json['errors']) > 0) {
            throw new \RuntimeException(
                'Inabit API GraphQL error: '.json_encode($json['errors'])
            );
        }

        return $json['data'] ?? [];
    }
}
