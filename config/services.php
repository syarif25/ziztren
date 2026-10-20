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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'eregistrasi' => [
        'url' => env('EREGISTRASI_API_URL', 'https://beeregistrasiv2.p2s3.com/api/v1'),
        'service_key' => env('EREGISTRASI_SERVICE_KEY'),
        'token' => env('EREGISTRASI_API_TOKEN', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJyb2xlIjoiaW50ZWdyYXRpb24iLCJpYXQiOjE3ODYyNDM1MDMsImV4cCI6MjEwMTgxOTUwM30.q2pHLV3XIDK5959WvmmUg1C5LreQm1NlGU8sU-c4LVo'),
    ],

];
