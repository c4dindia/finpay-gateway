<?php
// app/Services/InabitAuthService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class InabitAuthService
{
    public function getAccessToken(string $userType = 'admin'): string
    {
        $loginToken = $this->getLoginTokenFor($userType);

        if (! $loginToken) {
            throw new \RuntimeException("Missing Inabit login token for {$userType}");
        }

        $cacheKey = "inabit_access_token_{$userType}";

        // Cache for 10 minutes (access token lifetime ~15 mins)
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($loginToken) {
            $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$loginToken,
                    'Content-Type'  => 'application/json',
                ])
                ->post(config('services.inabit.base_url'), [
                    'query' => 'query Query { getApiUserAccessToken }',
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException(
                    'Failed to get Inabit access token: '.$response->status().' '.$response->body()
                );
            }

            $json = $response->json();

            if (isset($json['errors'])) {
                throw new \RuntimeException(
                    'Inabit GraphQL error while getting access token: '.json_encode($json['errors'])
                );
            }

            $token = $json['data']['getApiUserAccessToken'] ?? null;

            if (! $token) {
                throw new \RuntimeException('Inabit access token missing in response');
            }

            return $token;
        });
    }

    protected function getLoginTokenFor(string $userType): ?string
    {
        return match ($userType) {
            'admin'  => config('services.inabit.login_token_admin'),
            'signer' => config('services.inabit.login_token_signer'),
            default  => null,
        };
    }
}
