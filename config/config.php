<?php

return [
    'login_url' => env('BANKLY_LOGIN_URL', 'https://auth-mtls.sandbox.bankly.com.br/oauth2/token'), // Default value set to Production
    'api_url' => env('BANKLY_API_URL', 'https://api-mtls.sandbox.bankly.com.br'), // Defaults value set to Production
    'mtls_cert_path' => env('BANKLY_MTLS_CERT_PATH', null), // Path to mTLS cert
    'mtls_key_path' => env('BANKLY_MTLS_KEY_PATH', null), // Path to mTLS key
    'mtls_passphrase' => env('BANKLY_MTLS_PASSPHRASE', null), // mTLS passphrase
    'client_secret' => env('BANKLY_CLIENT_SECRET', null), // Your client secret provided by bankly staff
    'client_id' => env('BANKLY_CLIENT_ID', null), // Your client ID provided by bankly staff
    'company_key' => env('BANKLY_COMPANY_KEY', null), // Your company key provided by bankly staff
    'scope' => env('BANKLY_TOKEN_SCOPE', []), // Array or string of scopes
];
