<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\ScheduledPixRecurrenceInterface;

class ScheduledPixRecurrence extends \stdClass implements Arrayable, ScheduledPixRecurrenceInterface
{
    public string $initiationForm;

    public ?string $transactionIdentification;

    public float $interbankSettlementAmount;

    public string $requestDateTime;

    public string $dateSchedule;

    public ?string $endToEndId;

    public ?array $creditor;

    public array $debtor;

    public ?string $remittanceInformation;

    public ?string $frequency;

    public ?int $numberOfPayments;

    public ?string $endDate;

    public function toArray(): array
    {
        return array_filter((array) $this, fn ($value) => ! is_null($value));
    }
}
