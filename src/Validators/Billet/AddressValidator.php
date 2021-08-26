<?php

namespace WeDevBr\Bankly\Validators\Billet;

use WeDevBr\Bankly\Types\Billet\Address;

/**
 * AddressValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class AddressValidator
{
    /** @var Address */
    private $address;

    /**
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Validate the attributes of the address class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateAddressLine();
        $this->validateCity();
        $this->validateState();
        $this->validateZipCode();
    }

    /**
     * This validates the address line
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAddressLine()
    {
        $addressLine = $this->address->addressLine;
        if (empty($addressLine) || !is_string($addressLine)) {
            throw new \InvalidArgumentException('address line should be a string');
        }
    }

    /**
     * This validates the city address
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateCity()
    {
        $city = $this->address->city;
        if (empty($city) || !is_string($city)) {
            throw new \InvalidArgumentException('city address should be a string');
        }
    }

    /**
     * This validates the state address
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateState()
    {
        $state = $this->address->state;
        if (empty($state) || !is_string($state)) {
            throw new \InvalidArgumentException('state address should be a string');
        }
    }

    /**
     * This validates a zip code
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateZipCode()
    {
        $zipCode = $this->address->zipCode;
        if (empty($zipCode) || !is_string($zipCode)) {
            throw new \InvalidArgumentException('zip code should be a string');
        }
    }
}
