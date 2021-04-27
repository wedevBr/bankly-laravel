<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\CardValidator;

class Card extends \stdClass implements Arrayable
{
    /** @var string */
    public $documentNumber;

    /** @var string */
    public $cardName;

    /** @var string */
    public $alias;

    /** @var string */
    public $bankAgency;

    /** @var string */
    public $bankAccount;

    /** @var string */
    public $programId;

    /** @var string */
    public $password;

    /** @var \WeDevBr\Bankly\Types\Card\Address */
    public $address;

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
    public function validate()
    {
        $validator = new CardValidator($this);
        $validator->validate();

        return $this;
    }
}
