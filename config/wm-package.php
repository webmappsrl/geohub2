<?php

// config for Wm/WmPackage
return [
    'hoqu_url' => env('HOQU_URL', 'https://hoqu2.webmapp.it'),
    'hoqu_register_username' => env('HOQU_REGISTER_USERNAME'),
    'hoqu_register_password' => env('HOQU_REGISTER_PASSWORD '),
    'filesystems' => [
        'disks' => [
            'wmdumps' => [
                'driver' => 's3',
                'key' => env('AWS_DUMPS_ACCESS_KEY_ID'),
                'secret' => env('AWS_DUMPS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION'),
                'bucket' => env('AWS_DUMPS_BUCKET'),
                'url' => env('AWS_URL'),
                'endpoint' => env('AWS_ENDPOINT'),
            ]
        ]
    ]
];
