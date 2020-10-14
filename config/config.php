<?php

return [
    'login_url' => env('BANKLY_LOGIN_URL', 'https://login.sandbox.bankly.com.br/connect/token'), // Default value set to Production
    'api_url' => env(
        'BANKLY_API_URL',
        'https://api.sandbox.bankly.com.br'
    ), // Defaults value set to Production
    'client_secret' => env('BANKLY_CLIENT_SECRET', null), // Your client secret provided by bankly staff
    'client_id' => env('BANKLY_CLIENT_ID', null) // Your client ID provided by bankly staff
];
