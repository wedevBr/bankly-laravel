<?php

namespace WeDevBr\Bankly\Types\Card;

use WeDevBr\Bankly\Validators\Card\AddressValidator;

class Address extends \stdClass
{
    /** @var string */
    public string $neighborhood;

    /** @var string */
    public string $zipCode;

    /** @var string */
    public string $address;

    /** @var string */
    public string $number;

    /** @var string */
    public string $complement;

    /** @var string */
    public string $city;

    /** @var string */
    public string $state;

    /** @var string */
    public string $country;

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
    public function validate(): void
    {
        $validator = new AddressValidator($this);
        $validator->validate();
    }
}
