<?php

declare(strict_types=1);

namespace WeDevBr\Bankly\Requests\AutomaticPix;

use Carbon\Carbon;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\AuthorizationTypeEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\FrequencyTypeEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\RejectReasonEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\RetryPolicyEnum;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\Amount;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\Creditor;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\Debtor;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\OriginalDebtor;

class AuthorizeRequest
{
    public function __construct(
        public bool $accepted,
        public AuthorizationTypeEnum $authorizationType,
        public Debtor $debtor,
        public ?Amount $amount,
        public ?Creditor $creditor,
        public ?OriginalDebtor $originalDebtor,
        public ?Carbon $recurrenceCreationDateTime,
        public ?RetryPolicyEnum $retryPolicy,
        public ?string $endToEndId,
        public ?bool $scheduledPayment,
        public ?string $contractNumber,
        public ?string $description,
        public ?FrequencyTypeEnum $frequencyType,
        public ?Carbon $initialDateRecurrence,
        public ?Carbon $finalDateRecurrence,
        public ?RejectReasonEnum $rejectReason
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'accepted' => $this->accepted,
            'authorizationType' => $this->authorizationType->value,
            'debtor' => $this->debtor->toArray(),
            'amount' => $this->amount?->toArray(),
            'creditor' => $this->creditor?->toArray(),
            'originalDebtor' => $this->originalDebtor?->toArray(),
            'recurrenceCreationDateTime' => $this->recurrenceCreationDateTime?->toISOString(),
            'retryPolicy' => $this->retryPolicy?->value,
            'endToEndId' => $this->endToEndId,
            'scheduledPayment' => $this->scheduledPayment,
            'contractNumber' => $this->contractNumber,
            'description' => $this->description,
            'frequencyType' => $this->frequencyType?->value,
            'initialDateRecurrence' => $this->initialDateRecurrence?->toISOString(),
            'finalDateRecurrence' => $this->finalDateRecurrence?->toISOString(),
            'rejectReason' => $this->rejectReason?->value,
        ], fn ($value) => $value !== null);
    }
}
