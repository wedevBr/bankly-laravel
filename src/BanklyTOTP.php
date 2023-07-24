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
