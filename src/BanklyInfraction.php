<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\PixInfractionInterface;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;

class BanklyInfraction extends BaseHttpClient
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
                asJson: true,
            );
    }

    public function getInfractions(string $nifNumber, AddressingAccount $account): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this
            ->get(
                '/pix/branches/'.$account->branch.'/accounts/'.$account->number.'/infractions',
            );
    }

    public function findInfraction(string $nifNumber, AddressingAccount $account, string $protocolNumber): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this
            ->get(
                '/pix/branches/'.$account->branch.'/accounts/'.$account->number.'/infractions/'.$protocolNumber,
            );
    }

    public function cancelInfraction(string $nifNumber, AddressingAccount $account, string $protocolNumber): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this
            ->delete(
                '/pix/branches/'.$account->branch.'/accounts/'.$account->number.'/infractions/'.$protocolNumber,
            );
    }
}
