<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;
use WeDevBr\Bankly\Types\Pix\PixClaim;
use WeDevBr\Bankly\Enums\Pix\CancelReasonEnum;


class BanklyPixClaim
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
     * @param PixClaim $claimer
     * @return array|mixed
     */
    public function claim(PixClaim $claimer, string $nifNumber): mixed
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $nifNumber]);
        return $this->post('/pix/claims', $claimer->toArray(), asJson: true);
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
    * @param PixClaim $claimer
    * @return array|mixed
    */
   public function acknowledge(array $body, string $nifNumber): mixed
   {

       $this->setHeaders(['x-bkly-pix-user-id' => $nifNumber]);
       return $this->post('/pix/stub/claim/acknowledge', $body, asJson: true);
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
                'x-bkly-pix-user-id' => $nifNumber
            ]);

            $arr['reason'] = 'DONOR_REQUEST';
        return $this->patch('/pix/claims/' . $claimId . '/confirm', $arr, asJson: true);
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
                'x-bkly-pix-user-id' => $nifNumber
            ]);
        $arr['reason'] = 'DONOR_REQUEST';


        return $this->patch('/pix/claims/' . $claimId . '/complete', $arr, asJson: true);
    }

     /**
     * @param array $claimer
     * @param string $status
     * @return array|mixed
     */
    public function cancel(string $nifNumber, string $claimId, CancelReasonEnum $reason): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-pix-user-id' => $nifNumber
            ]);


        return $this->patch('/pix/claims/' . $claimId . '/cancel', ["reason" => $reason->value], asJson: true);
    }
}
