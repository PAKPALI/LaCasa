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

    'kprimesms' => [
        'base_url' => env('KPRIME_SMS_BASE_URL'),
        'token' => env('KPRIME_SMS_TOKEN'),
        'key' => env('KPRIME_SMS_KEY'),
        'sender' => env('KPRIME_SMS_SENDER'),
        'sender_id' => env('KPRIME_SMS_SENDER_ID'),
        'response_url' => env('KPRIME_SMS_RESPONSE_URL'),
    ],
    // KPRIME_SMS_BASE_URL=https:https://api.kprimesms.com/v1 
    // KPRIME_SMS_TOKEN=5mNk3ivYwF4dcnWyLqUeucfvDlWefayI
    // KPRIME_SMS_KEY=51b001f55cec3e0db82773d2dd429eb3
    // KPRIME_SMS_SENDER= LaCasa
    // KPRIME_SMS_SENDER_ID= 0Xi69404c43ca61axb0

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
