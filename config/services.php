<?php

return [
    /*
    |--------------------------------------------------------------------------
    | External Vehicles API
    |--------------------------------------------------------------------------
    | Configuration for the external Modo vehicles data source.
    | Timeout is in seconds.
    */
    'vehicles_api' => [
        'url'     => env('VEHICLES_API_URL', 'https://mock-api.net/api/modo/vehicles'),
        'timeout' => (int) env('VEHICLES_API_TIMEOUT', 10),
    ],
];