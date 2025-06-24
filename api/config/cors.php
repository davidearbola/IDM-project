<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Assicurati che entrambi i percorsi siano qui

    'allowed_methods' => ['*'],

    // QUI LA MODIFICA CHIAVE: specifica l'URL esatto del frontend
    'allowed_origins' => explode(',', env('FRONTEND_URL', 'http://localhost:5173')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // QUI LA MODIFICA CHIAVE: deve essere TRUE
    'supports_credentials' => true,

];