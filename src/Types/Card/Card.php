<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\CardValidator;

class Card extends \stdClass implements Arrayable
{
    /** @var string */
    public string $documentNumber;

    /** @var string */
    public string $cardName;

    /** @var string */
    public string $alias;

    /** @var string */
    public string $bankAgency;

    /** @var string */
    public string $bankAccount;

    /** @var string */
    public string $programId;

    /** @var string */
    public string $password;

    /** @var Address|array */
    public Address|array $address;

    /** @var string */
    public string $type;

    /**
     * This validate and return an array
     * @return array
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
