<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use WeDevBr\Bankly\Enums\Pix\ScheduledPix\LiquidationCodeSubTypeEnum;
use WeDevBr\Bankly\Enums\Pix\ScheduledPix\StatusEnum;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;

class BanklyScheduledPix extends BaseHttpClient
{
    public function getScheduledPix(
        string $nifNumber,
        ?LiquidationCodeSubTypeEnum $liquidationCodeSubType = null,
        ?string $debtorAccountIdentification = null,
        ?string $debtorAccountIssuer = null,
        ?StatusEnum $status = null,
        ?Carbon $initialDate = null,
        ?Carbon $endDate = null,
        ?int $page = null,
        ?int $pageSize = null,
    ): array {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->get('/pix/scheduling-payments',
            array_filter([
                'liquidationCodeSubType' => $liquidationCodeSubType?->value,
                'debtorAccountIdentification' => $debtorAccountIdentification,
                'debtorAccountIssuer' => $debtorAccountIssuer,
                'status' => $status?->value,
                'initialDate' => $initialDate?->format('Y-m-d'),
                'endDate' => $endDate?->format('Y-m-d'),
                'page' => $page,
                'pageSize' => $pageSize,
            ])
        );
    }

    public function getScheduledPixById(string $id, string $nifNumber): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->get('/pix/scheduling-payments/'.$id);
    }

    public function cancelScheduledPixById(string $id, string $nifNumber): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->delete('/pix/scheduling-payments/'.$id);
    }
}
