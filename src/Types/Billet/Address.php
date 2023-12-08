<?php

namespace WeDevBr\Bankly\Types\Billet;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Billet\AddressValidator;

class Address implements Arrayable
{
    public ?string $addressLine;

    public ?string $city;

    public ?string $state;

    public ?string $zipCode;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate a Addressing Account
     */
    public function validate(): void
    {
        $addressValidator = new AddressValidator($this);
        $addressValidator->validate();
    }
}
