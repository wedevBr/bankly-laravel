<?php

namespace WeDevBr\Bankly\Traits;

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
     */
    public function setKeyPath(string $path): self
    {
        $this->mtlsKey = $path;

        return $this;
    }

    /**
     * @return $this
     */
    public function setPassphrase(string $passphrase): self
    {
        $this->mtlsPassphrase = $passphrase;

        return $this;
    }
}
