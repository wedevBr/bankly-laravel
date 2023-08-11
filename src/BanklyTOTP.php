<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\TOTP\TOTP;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

class BanklyTOTP
{
    use Rest;

    private string $documentNumber;

    /**
     * @param string|null $mtlsPassphrase
     */
    public function __construct(string $mtlsPassphrase = null)
    {
        $this->mtlsCert = config('bankly')['mtls_cert_path'] ?? null;
        $this->mtlsKey = config('bankly')['mtls_key_path'] ?? null;
        $this->mtlsPassphrase = $mtlsPassphrase ?? config('bankly')['mtls_passphrase'];
    }

    /**
     * Set the cert.crt file path
     * @param string $path
     * @return self
     */
    public function setCertPath(string $path): self
    {
        $this->mtlsCert = $path;
        return $this;
    }

    /**
     * Set the cert.pem file path
     * @param string $path
     * @return self
     */
    public function setKeyPath(string $path): self
    {
        $this->mtlsKey = $path;
        return $this;
    }

    /**
     * @param string $passphrase
     * @return $this
     */
    public function setPassphrase(string $passphrase)
    {
        $this->mtlsPassphrase = $passphrase;
        return $this;
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
     * @param TOTP $totp
     * @return array|mixed
     * @throws RequestException
     */
    public function createTOTP(TOTP $totp): mixed
    {
        $this->setHeaders(['x-bkly-user-id' => $this->getDocumentNumber()]);
        return $this->post('/totp', $totp->toArray());
    }


    /**
     * @param string $hash
     * @param string $code
     * @return mixed
     * @throws RequestException
     */
    public function verifyTOTP(string $hash, string $code): mixed
    {
        $this->setHeaders(['x-bkly-user-id' => $this->getDocumentNumber()]);
        return $this->patch('/totp', [
            'hash' => $hash,
            'code' => $code
        ]);
    }
}
