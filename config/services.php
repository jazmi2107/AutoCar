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

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'url' => env('OPENAI_API_URL', 'https://api.openai.com/v1'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY', 'AIzaSyCziCeDmXEcKcayGX8CkuDWQ_OBctigFW8'),
    ],

    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY') ?: (getenv('FIREBASE_API_KEY') ?: ($_SERVER['FIREBASE_API_KEY'] ?? env('VITE_FIREBASE_API_KEY'))),
        'database_url' => env('FIREBASE_DATABASE_URL') ?: (getenv('FIREBASE_DATABASE_URL') ?: ($_SERVER['FIREBASE_DATABASE_URL'] ?? null)),
    ],

];
