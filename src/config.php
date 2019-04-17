<?php
return $config = [
    'links' => [
        [
            'rel' => 'manifest',
            'href' => '/manifest.webmanifest',
        ],
        [
            'rel' => 'license',
            'href' => '/LICENSE',
        ],
    ],
    'meta' => [
        [
            'name' => 'viewport',
            'content' => 'width=device-width, initial-scale=1',
        ],
        [
            'name' => 'theme-color',
            'content' => '#000',
        ],
        [
            'name' => 'application-name',
            'content' => '',
        ],
        [
            'name' => 'apple-mobile-web-app-title',
            'content' => 'title',
        ],
        [
            'name' => 'mobile-web-app-capable',
            'content' => 'yes',
        ],
        [
            'name' => 'apple-mobile-web-app-capable',
            'content' => 'yes',
        ],
        [
            'name' => 'viewport',
            'content' => 'width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0',
        ],
        [
            'http-equiv' => 'Content-Type',
            'content' => "text/html; charset=utf-8",
        ],
        [
            'http-equiv' => 'Cache-Control',
            'content' => "no-cache, no-store, must-revalidate",
        ],
        [
            'http-equiv' => 'Pragma',
            'content' => "no-cache",
        ],
        [
            'http-equiv' => 'Expires',
            'content' => "0",
        ],
        // [
        //     'http-equiv' => 'Content-Security-Policy',
        //     'content' => "default-src 'self'",
        // ],
    ]
];
