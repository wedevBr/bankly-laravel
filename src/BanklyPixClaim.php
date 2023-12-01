<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;
use WeDevBr\Bankly\Types\Pix\PixClaim;


class BanklyPixClaim
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
     * @param PixClaim $claimer
     * @return array|mixed
     */
    public function claim(PixClaim $claimer, string $nifNumber): mixed
    {
        $this->setHeaders(['x-bkly-user-id' => $nifNumber]);
        return $this->post('/pix/claims', $claimer->toArray());
    }

     /**
     * @param PixClaim $claimer
     * @return array|mixed
     */
    public function acknowledge(PixClaim $claimer, string $nifNumber): mixed
    {
        $this->setHeaders(['x-bkly-user-id' => $nifNumber]);
        return $this->post('/pix/stub/claim/acknowledge', $claimer->toArray());
    }

    /**
     * @param string $claimer
     * @param string $status
     * @return array|mixed
     */
    public function read(string $nifNumber, string $claimer, string $status = null): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-pix-user-id' => $nifNumber
            ]);


        $query = ['documentNumber' => $nifNumber, 'claimsFrom' => $claimer, 'status' => $status];

        return $this->get('/pix/claims', array_filter($query));
    }

    /**
     * @param array $claimer
     * @param string $status
     * @return array|mixed
     */
    public function confirm(string $nifNumber, string $claimId): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-user-id' => $nifNumber
            ]);


        return $this->patch('/pix/claims/' + $claimId + '/confirm');
    }

    /**
     * @param array $claimer
     * @param string $status
     * @return array|mixed
     */
    public function complete(string $nifNumber, string $claimId): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-user-id' => $nifNumber
            ]);


        return $this->patch('/pix/claims/' + $claimId + '/complete');
    }

     /**
     * @param array $claimer
     * @param string $status
     * @return array|mixed
     */
    public function cancel(string $nifNumber, string $claimId): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-user-id' => $nifNumber
            ]);


        return $this->patch('/pix/claims/' + $claimId + '/cancel', ["reason" => "DONOR_REQUEST"]);
    }
}
