<?php

namespace WeDevBr\Bankly\Traits;

use WeDevBr\Bankly\BanklyBillet;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyPixClaim;
use WeDevBr\Bankly\BanklyTOTP;

trait Mtls
{
    protected ?string $mtlsCert = null;

    protected mixed $mtlsKey = null;

    protected ?string $mtlsPassphrase = null;

    /**
     * Set the cert.crt file path
     */
    public function setCertPath(string $path): self
    {
        $this->mtlsCert = $path;

        return $this;
    }

    /**
     * Set the cert.pem file path
     *
     * @return BanklyBillet|BanklyCard|BanklyPixClaim|BanklyTOTP|Mtls
     */
    public function setKeyPath(string $path): self
    {
        $this->mtlsKey = $path;

        return $this;
    }

    /**
     * @return BanklyBillet|BanklyCard|BanklyPixClaim|BanklyTOTP|Mtls
     */
    public function setPassphrase(string $passphrase): self
    {
        $this->mtlsPassphrase = $passphrase;

        return $this;
    }
}
