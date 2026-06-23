<?php

namespace WeDevBr\Bankly\Types\Credit;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CreditLimitAcceptanceInterface;

class CreditLimitAcceptance extends \stdClass implements Arrayable, CreditLimitAcceptanceInterface
{
    public string $documentNumber;

    public string $contract;

    public ?bool $accepted;

    public function toArray(): array
    {
        return (array) $this;
    }
}
