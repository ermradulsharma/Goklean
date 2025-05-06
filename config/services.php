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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'msg91' => [
        'key' => env('MSG91_KEY', '198234AGZaqaLQ5a849563'),
        'endpoint' => env('MSG91_ENDPOINT', 'https://api.msg91.com/api/v5/flow/'),
    ],

    'razorpay' => [
        'key' => env('RZP_KEY', 'rzp_test_jVgJ4rMrp2asu6'),
        'secret' => env('RZP_SECRET', 'y1wlveWdm1cl2cuZ7KVzbc6P'),
        'webhook_secret' => env('RZP_WH_SECRET', '19hguAGZaqaLQ5a85463'),
    ],

    'fcm' => [
        'endpoint' => env('FCM_ENDPOINT', 'https://fcm.googleapis.com/fcm/send'),
        'key' => env('FCM_KEY', 'AAAAVrcdHuM:APA91bEG8n01TvxeToSe1G4qCNMxlWz9fBadyYkmJnKSaUuQmkQr6AzH3EwBIB4W0r056mF6xybohP1I-GqMMa3fP_bi9jvjdBnX2ziBALx1DNY6Yqypw6g7ZNs34g9BeDa4nqFJv8rt'),
    ],

];
