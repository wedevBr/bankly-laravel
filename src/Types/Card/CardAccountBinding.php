<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CardAccountBindingInterface;

class CardAccountBinding extends \stdClass implements Arrayable, CardAccountBindingInterface
{
    public string $bankAgency;

    public string $bankAccount;

    public ?string $documentNumber;

    public function toArray(): array
    {
        return (array) $this;
    }
}
