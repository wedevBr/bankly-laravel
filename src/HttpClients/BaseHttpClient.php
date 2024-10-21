<?php

namespace WeDevBr\Bankly\HttpClients;

use WeDevBr\Bankly\Traits\Mtls;
use WeDevBr\Bankly\Traits\Rest;

class BaseHttpClient
{
    use Mtls;
    use Rest;

    public function __construct(?string $mtlsPassphrase = null)
    {
        $this->mtlsCert = config('bankly')['mtls_cert_path'] ?? null;
        $this->mtlsKey = config('bankly')['mtls_key_path'] ?? null;
        $this->mtlsPassphrase = $mtlsPassphrase ?? config('bankly')['mtls_passphrase'];
    }
}
