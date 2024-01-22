<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Mtls;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\TOTP\TOTP;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

class BanklyTOTP
{
    use Mtls;
    use Rest;

    private string $documentNumber;

    public function __construct(?string $mtlsPassphrase = null)
    {
        $this->mtlsCert = config('bankly')['mtls_cert_path'] ?? null;
        $this->mtlsKey = config('bankly')['mtls_key_path'] ?? null;
        $this->mtlsPassphrase = $mtlsPassphrase ?? config('bankly')['mtls_passphrase'];
    }

    public function setDocumentNumber(string $documentNumber): void
    {
        (new CpfCnpjValidator($documentNumber))->validate();
        $this->documentNumber = $documentNumber;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function createTOTP(TOTP $totp): mixed
    {
        $this->setHeaders(['x-bkly-user-id' => $this->getDocumentNumber()]);

        return $this->post('/totp', $totp->toArray(), asJson: true);
    }

    /**
     * @throws RequestException
     */
    public function verifyTOTP(string $hash, string $code): mixed
    {
        $this->setHeaders(['x-bkly-user-id' => $this->getDocumentNumber()]);

        return $this->patch('/totp', [
            'hash' => $hash,
            'code' => $code,
        ], asJson: true);
    }
}
