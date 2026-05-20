<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'inabit' => [
        'approver_url' => env('INABIT_APPROVER_URL', 'http://localhost:3020'),
        'base_url' => env('INABIT_GRAPHQL_URL', 'https://api.inabit.com/graphql'),

        // login tokens (long-lived, used only to fetch access tokens)
        'login_token_admin'    => env('INABIT_LOGIN_TOKEN_ADMIN'),
        'login_token_signer'   => env('INABIT_LOGIN_TOKEN_SIGNER'),

        // org & defaults
        'organization_id'      => env('INABIT_ORGANIZATION_ID'),
        'blockchain_id'        => env('INABIT_BLOCKCHAIN_ID'),
        'asset_id'             => env('INABIT_ASSET_ID'),
    ],
    
    'p23' => [
        'payment_expiry_minutes' => '3',
    ],

];
