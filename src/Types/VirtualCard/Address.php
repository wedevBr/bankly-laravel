<?php

namespace WeDevBr\Bankly\Types\VirtualCard;

use WeDevBr\Bankly\Validators\VirtualCard\AddressValidator;

class Address extends \stdClass
{
    /** @var string */
    public $neighborhood;

    /** @var string */
    public $zipCode;

    /** @var string */
    public $address;

    /** @var string */
    public $number;

    /** @var string */
    public $complement;

    /** @var string */
    public $city;

    /** @var string */
    public $state;

    /** @var string */
    public $country;

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
     * This function validate a virtual card address
     */
    public function validate()
    {
        $validator = new AddressValidator($this);
        $validator->validate();
    }
}
