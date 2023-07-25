<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\AddressValidator;

class Address
{
    /** @var string|null */
    public ?string $addressLine;

    /** @var string|null */
    public ?string $city;

    /** @var string|null */
    public ?string $state;

    /** @var string|null */
    public ?string $zipCode;

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
    public function validate(): void
    {
        $addressValidator = new AddressValidator($this);
        $addressValidator->validate();
    }
}
