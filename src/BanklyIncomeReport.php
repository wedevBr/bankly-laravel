<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Rest;

class BanklyIncomeReport

{
    use Rest;

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


    /**
     * Returns the income report for a given year
     *
     * @param string $account
     * @param string|null $year If not informed, the previous year will be used
     * @return array|mixed
     * @throws RequestException
     */
    public function getIncomeReport(string $account, string $year = null): mixed
    {
        $this->setApiVersion('2.0');

        return $this->get('/accounts/' . $account . '/income-report', [
            'calendar' => $year
        ]);
    }

}
