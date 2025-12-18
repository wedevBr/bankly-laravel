<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\PixInfractionInterface;

class BanklyInfraction  extends BaseHttpClient
{
    public function createInfraction(string $nifNumber, PixInfractionInterface $pixInfraction): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this
            ->post(
                '/pix/branches/'.$pixInfraction->branch.'/accounts/'.$pixInfraction->account.'/infractions',
                $pixInfraction->toArray(),
            );
    }
}
