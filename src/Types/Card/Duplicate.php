<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\DuplicateCardValidator;

class Duplicate extends \stdClass implements Arrayable
{
    /** @var string */
    public $status;

    /** @var string */
    public $documentNumber;

    /** @var string */
    public $description;
    
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
     * This function validate the data for duplicate card
     */
    public function validate()
    {
        $validator = new DuplicateCardValidator($this);
        $validator->validate();

        return $this;
    }
}
