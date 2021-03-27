<?php

/**
 * BankAccountValidator class
 *
 * PHP version 7.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Adeildo Amorim <adeildo@capitaldigitalaberto.com.br>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */

namespace WeDevBr\Bankly\Validators\VirtualCard;

use WeDevBr\Bankly\Types\VirtualCard\Address;

class AddressValidator
{
    private $address;

    /**
     * AddressValidator constructor.
     * @param Address $bankAccount
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * This validate the virtual card
     */
    public function validate(): void
    {
        $this->validateZipCode();
        $this->validateAddress();
        $this->validateNumber();
        $this->validateNeighborhood();
        $this->validateCity();
        $this->validateState();
        $this->validateCountry();
    }

    /**
     * This validate a zip code
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateZipCode()
    {
        $zipCode = $this->address->zipCode;
        if (empty($zipCode) || !is_string($zipCode) || !is_numeric($zipCode)) {
            throw new \InvalidArgumentException('zip code should be a numeric string');
        }
    }

    /**
     * This validates a address
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAddress()
    {
        $address = $this->address->address;
        if (empty($address) || !is_string($address)) {
            throw new \InvalidArgumentException('address should be a string');
        }
    }

    /**
     * This validate a address number
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateNumber()
    {
        $number = $this->address->number;
        if (empty($number) || !is_string($number) || !is_numeric($number)) {
            throw new \InvalidArgumentException('number should be a numeric string');
        }
    }

    /**
     * This validate a virtual card neighborhood
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateNeighborhood()
    {
        $neighborhood = $this->address->neighborhood;
        if (empty($neighborhood) || !is_string($neighborhood)) {
            throw new \InvalidArgumentException('neighborhood should be a string');
        }
    }

    /**
     * This validate a virtual card city
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateCity()
    {
        $city = $this->address->city;
        if (empty($city) || !is_string($city)) {
            throw new \InvalidArgumentException('city should be a string');
        }
    }

    /**
     * This validate a virtual card state
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateState()
    {
        $state = $this->address->state;
        if (empty($state) || !is_string($state)) {
            throw new \InvalidArgumentException('state should be a string');
        }
    }

    /**
     * This validate a virtual card country
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateCountry()
    {
        $country = $this->address->country;
        if (empty($country) || !is_string($country)) {
            throw new \InvalidArgumentException('country should be a string');
        }
    }
}
