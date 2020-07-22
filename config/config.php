<?php

return [
    'api_url' => env('BANKLY_LOGIN_URL', 'https://api.acessobank.com.br/baas'), // Default value set to Production
    'login_url' => env('BANKLY_API_URL', 'https://login.acessobank.com.br'), // Defaults value set to Production
    'client_secret' => env('BANKLY_CLIENT_SECRET', null), // Your client secret provided by bankly staff
    'client_id' => env('BANKLY_CLIENT_ID', null) // Your client ID provided by bankly staff
];
