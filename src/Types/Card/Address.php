<?php

namespace WeDevBr\Bankly\Types\Card;

use WeDevBr\Bankly\Validators\Card\AddressValidator;

class Address extends \stdClass
{
    /** @var string|null */
    public ?string $neighborhood;

    /** @var string|null */
    public ?string $zipCode;

    /** @var string|null */
    public ?string $address;

    /** @var string|null */
    public ?string $number;

    /** @var string */
    public string $complement;

    /** @var string|null */
    public ?string $city;

    /** @var string|null */
    public ?string $state;

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
