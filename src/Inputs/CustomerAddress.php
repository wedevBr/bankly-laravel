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
    protected string $zipCode;

    /** @var string */
    protected string $addressLine;

    /** @var string */
    protected string $buildingNumber;

    /** @var ?string */
    protected ?string $complement = null;

    /** @var string */
    protected string $neighborhood;

    /** @var string */
    protected string $city;

    /** @var string */
    protected string $state;

    /** @var string */
    protected string $country;

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
     * @param string $addressLine
     * @return CustomerAddress
     */
    public function setAddressLine(string $addressLine): CustomerAddress
    {
        $this->addressLine = $addressLine;
        return $this;
    }

    /**
     * @param string $buildingNumber
     * @return CustomerAddress
     */
    public function setBuildingNumber(string $buildingNumber): CustomerAddress
    {
        $this->buildingNumber = $buildingNumber;
        return $this;
    }

    /**
     * @param string $complement
     * @return CustomerAddress
     */
    public function setComplement(string $complement): CustomerAddress
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * @param string $neighborhood
     * @return CustomerAddress
     */
    public function setNeighborhood(string $neighborhood): CustomerAddress
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    /**
     * @param string $city
     * @return CustomerAddress
     */
    public function setCity(string $city): CustomerAddress
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $state
     * @return CustomerAddress
     */
    public function setState(string $state): CustomerAddress
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @param string $country
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
