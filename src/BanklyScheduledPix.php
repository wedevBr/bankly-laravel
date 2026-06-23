<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use WeDevBr\Bankly\Enums\Pix\ScheduledPix\LiquidationCodeSubTypeEnum;
use WeDevBr\Bankly\Enums\Pix\ScheduledPix\StatusEnum;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\ScheduledPixPaymentInterface;
use WeDevBr\Bankly\Support\Contracts\ScheduledPixRecurrenceInterface;

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

    /**
     * Create a single scheduled Pix payment.
     *
     * @param  string  $nifNumber  User document number.
     * @param  ScheduledPixPaymentInterface  $payment  The scheduled payment data.
     */
    public function createScheduledPix(string $nifNumber, ScheduledPixPaymentInterface $payment): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->post('/pix/scheduling-payments', $payment->toArray(), null, true);
    }

    /**
     * Create a recurring scheduled Pix payment.
     *
     * @param  string  $nifNumber  User document number.
     * @param  ScheduledPixRecurrenceInterface  $recurrence  The recurrence data.
     */
    public function createRecurrence(string $nifNumber, ScheduledPixRecurrenceInterface $recurrence): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->post('/pix/scheduling-payments/recurrences', $recurrence->toArray(), null, true);
    }

    /**
     * List recurring scheduled Pix payments.
     *
     * @param  string  $nifNumber  User document number.
     * @param  array  $filters  Optional query filters.
     */
    public function getRecurrences(string $nifNumber, array $filters = []): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->get('/pix/scheduling-payments/recurrences', $filters);
    }

    /**
     * Query a recurrence by its identifier.
     *
     * @param  string  $requestIdentifier  Recurrence identifier.
     * @param  string  $nifNumber  User document number.
     */
    public function getRecurrenceById(string $requestIdentifier, string $nifNumber): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->get('/pix/scheduling-payments/recurrences/'.$requestIdentifier);
    }

    /**
     * Cancel a recurrence by its identifier.
     *
     * @param  string  $requestIdentifier  Recurrence identifier.
     * @param  string  $nifNumber  User document number.
     */
    public function cancelRecurrence(string $requestIdentifier, string $nifNumber): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->delete('/pix/scheduling-payments/recurrences/'.$requestIdentifier);
    }
}
