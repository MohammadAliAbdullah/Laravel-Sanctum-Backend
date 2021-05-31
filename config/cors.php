<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    //Modify paths to indicate which endpoints need to be protected: in this case api/* and sanctum/csrf-cookie.
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
   
    // 'allowed_origins' => ['*'],
    // Modify allowed-origins to specify the urls from which requests will be accepted. This will be the production and development urls of your React app, https://auth.bob-humphrey.com (for my app) and http://localhost:3000.
    'allowed_origins' => ['http://127.0.0.1:8000/', 'http://localhost:3000'],
    
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // 'supports_credentials' => false,
    
    // Then set support_credentials to true.
    'supports_credentials' => true,

];
