<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Enums\Pix\CancelReasonEnum;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Types\Pix\PixClaim;

class BanklyPixClaim extends BaseHttpClient
{
    /**
     * @return array|mixed
     */
    public function claim(PixClaim $claimer, string $nifNumber): mixed
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $nifNumber]);

        return $this->post('/pix/claims', $claimer->toArray(), asJson: true);
    }

    /**
     * @return array|mixed
     */
    public function read(string $nifNumber, string $claimer, ?string $status = null): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-pix-user-id' => $nifNumber,
            ]);

        $query = ['documentNumber' => $nifNumber, 'claimsFrom' => $claimer, 'status' => $status];

        return $this->get('/pix/claims', array_filter($query));
    }

    /**
     * @param  PixClaim  $claimer
     * @return array|mixed
     */
    public function acknowledge(array $body, string $nifNumber): mixed
    {

        $this->setHeaders(['x-bkly-pix-user-id' => $nifNumber]);

        return $this->post('/pix/stub/claim/acknowledge', $body, asJson: true);
    }

    /**
     * @param  array  $claimer
     * @param  string  $status
     * @return array|mixed
     */
    public function confirm(string $nifNumber, string $claimId): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-pix-user-id' => $nifNumber,
            ]);

        $arr['reason'] = 'DONOR_REQUEST';

        return $this->patch('/pix/claims/'.$claimId.'/confirm', $arr, asJson: true);
    }

    /**
     * @param  array  $claimer
     * @param  string  $status
     * @return array|mixed
     */
    public function complete(string $nifNumber, string $claimId): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-pix-user-id' => $nifNumber,
            ]);
        $arr['reason'] = 'DONOR_REQUEST';

        return $this->patch('/pix/claims/'.$claimId.'/complete', $arr, asJson: true);
    }

    /**
     * @param  array  $claimer
     * @param  string  $status
     * @return array|mixed
     */
    public function cancel(string $nifNumber, string $claimId, CancelReasonEnum $reason): mixed
    {
        $this->setHeaders(
            [
                'x-bkly-pix-user-id' => $nifNumber,
            ]);

        return $this->patch('/pix/claims/'.$claimId.'/cancel', ['reason' => $reason->value], asJson: true);
    }
}
