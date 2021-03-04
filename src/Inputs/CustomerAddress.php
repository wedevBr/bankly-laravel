<?php

namespace WeDevBr\Bankly\Inputs;

use WeDevBr\Bankly\Support\Contracts\CustomerAddressInterface;

/**
 * CustomerAddress class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CustomerAddress implements CustomerAddressInterface
{
    /** @var string */
    protected $zipCode;

    /** @var string */
    protected $addressLine;

    /** @var string */
    protected $buildingNumber;

    /** @var string */
    protected $complement;

    /** @var string */
    protected $neighborhood;

    /** @var string */
    protected $city;

    /** @var string */
    protected $state;

    /** @var string */
    protected $country;

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setZipCode(string $zipCode): CustomerAddress
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setAddressLine(string $addressLine): CustomerAddress
    {
        $this->addressLine = $addressLine;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setBuildingNumber(string $buildingNumber): CustomerAddress
    {
        $this->buildingNumber = $buildingNumber;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setComplement(string $complement): CustomerAddress
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setNeighborhood(string $neighborhood): CustomerAddress
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setCity(string $city): CustomerAddress
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setState(string $state): CustomerAddress
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return CustomerAddress
     */
    public function setCountry(string $country): CustomerAddress
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function getAddressLine(): string
    {
        return $this->addressLine;
    }

    /**
     * @return string
     */
    public function getBuildingNumber(): string
    {
        return $this->buildingNumber;
    }

    /**
     * @return string
     */
    public function getComplement(): string
    {
        return $this->complement;
    }

    /**
     * @return string
     */
    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'neighborhood' => $this->neighborhood,
            'complement' => $this->complement,
            'buildingNumber' => $this->buildingNumber,
            'addressLine' => $this->addressLine,
            'zipCode' => $this->zipCode,
        ];
    }
}
