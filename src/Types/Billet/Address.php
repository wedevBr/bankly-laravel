<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\AddressValidator;

class Address
{
    /** @var string */
    public $addressLine;

    /** @var string */
    public $city;

    /** @var string */
    public $state;

    /** @var string */
    public $zipCode;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return (array) $this;
    }

    /**
     * This function validate a Addressing Account
     */
    public function validate()
    {
        $addressValidator = new AddressValidator($this);
        $addressValidator->validate();
    }
}
