<?php

namespace WeDevBr\Bankly\Types\Credit;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CreditAcceptanceInterface;

class CreditAcceptance extends \stdClass implements Arrayable, CreditAcceptanceInterface
{
    public string $contract;

    public string $documentNumber;

    public string $dataHash;

    public function toArray(): array
    {
        return (array) $this;
    }
}
