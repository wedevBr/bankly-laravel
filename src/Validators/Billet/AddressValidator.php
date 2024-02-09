<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\Address;

/**
 * AddressValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class AddressValidator
{
    private Address $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Validate the attributes of the address class
     */
    public function validate(): void
    {
        $this->validateAddressLine();
        $this->validateCity();
        $this->validateState();
        $this->validateZipCode();
        $this->validateNeighborhood();
    }

    /**
     * This validates the address line
     *
     * @throws InvalidArgumentException
     */
    private function validateAddressLine(): void
    {
        $addressLine = $this->address->addressLine;
        if (empty($addressLine) || ! is_string($addressLine)) {
            throw new InvalidArgumentException('address line should be a string');
        }
    }

    /**
     * This validates the city address
     *
     * @throws InvalidArgumentException
     */
    private function validateCity(): void
    {
        $city = $this->address->city;
        if (empty($city) || ! is_string($city)) {
            throw new InvalidArgumentException('city address should be a string');
        }
    }

    /**
     * This validates the state address
     *
     * @throws InvalidArgumentException
     */
    private function validateState(): void
    {
        $state = $this->address->state;
        if (empty($state) || ! is_string($state)) {
            throw new InvalidArgumentException('state address should be a string');
        }
    }

    /**
     * This validates a zip code
     *
     * @throws InvalidArgumentException
     */
    private function validateZipCode(): void
    {
        $zipCode = $this->address->zipCode;
        if (empty($zipCode) || ! is_string($zipCode)) {
            throw new InvalidArgumentException('zip code should be a string');
        }
    }

    private function validateNeighborhood(): void
    {
        $neighborhood = $this->address->neighborhood;
        if (empty($neighborhood) || ! is_string($neighborhood)) {
            throw new InvalidArgumentException('neighborhood should be a string');
        }
    }
}
