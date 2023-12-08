<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\CardValidator;

class Card extends \stdClass implements Arrayable
{
    public ?string $documentNumber;

    public ?string $cardName;

    public string $alias;

    public string $bankAgency;

    public string $bankAccount;

    public ?string $programId = null;

    public string $password;

    public Address|array $address;

    public string $type;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();
        $this->address = $this->address->toArray();

        return (array) $this;
    }

    /**
     * This function validate a card address
     */
    public function validate(): self
    {
        $validator = new CardValidator($this);
        $validator->validate();

        return $this;
    }
}
