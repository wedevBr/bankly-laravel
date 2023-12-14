<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\AddressValidator;

class Address extends \stdClass implements Arrayable
{
    public ?string $neighborhood;

    public ?string $zipCode;

    public ?string $address;

    public ?string $number;

    public string $complement;

    public ?string $city;

    public ?string $state;

    public string $country;

    /**
     * This validate and return an array
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
