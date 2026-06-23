<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CardBindingInterface;

class CardBinding extends \stdClass implements Arrayable, CardBindingInterface
{
    public string $documentNumber;

    public string $bankAgency;

    public string $bankAccount;

    public ?string $bankAccountType;

    public function toArray(): array
    {
        return (array) $this;
    }
}
