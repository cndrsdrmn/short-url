<?php

return [
    /*
    |--------------------------------------------------------------------------
    | URL Prefix
    |--------------------------------------------------------------------------
    |
    | This value sets the prefix for the short URL routes. It will be used as the
    | base path for accessing the short URLs.
    |
    */
    'prefix' => '/s',

    /*
    |--------------------------------------------------------------------------
    | Allowed Schemas
    |--------------------------------------------------------------------------
    |
    | Specify the schema can be allowed for generated token.
    |
    */
    'allowed_schemas' => [
        'http',
        'https',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Specify the database connection to be used for storing short URLs. If null,
    | the default connection defined in the database configuration will be used.
    |
    */
    'connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Define the middleware to apply to the short URL routes. By default, the routes
    | are throttled to prevent abuse. You can modify or extend the middleware stack
    | as needed.
    |
    */
    'middleware' => [
        'throttle:100,1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Format
    |--------------------------------------------------------------------------
    |
    | Specify the format used to generate tokens for short URLs. Supported values:
    | - "bothify": Mix of letters and numbers (e.g., `A1b2C`)
    | - "numerify": Numbers only (e.g., `12345`)
    | - "lexify": Letters only (e.g., `ABCDE`)
    |
    */
    'token_format' => 'bothify',

    /*
    |--------------------------------------------------------------------------
    | Token Length
    |--------------------------------------------------------------------------
    |
    | Define the length of the token generated for the short URLs. The token's
    | length determines how many characters will be included in the generated URL.
    |
    */
    'token_length' => 5,

    /*
    |--------------------------------------------------------------------------
    | Block Bots
    |--------------------------------------------------------------------------
    |
    | If enabled, requests from known bots will be blocked from accessing the
    | short URLs. This is useful for preventing automated access and ensuring
    | accurate analytics.
    |
    */
    'should_block_bots' => true,
];
