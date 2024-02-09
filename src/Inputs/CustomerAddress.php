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
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CustomerAddress implements CustomerAddressInterface
{
    protected string $zipCode;

    protected string $addressLine;

    protected string $buildingNumber;

    /** @var ?string */
    protected ?string $complement = null;

    protected string $neighborhood;

    protected string $city;

    protected string $state;

    protected string $country;

    public function setZipCode(string $zipCode): CustomerAddress
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function setAddressLine(string $addressLine): CustomerAddress
    {
        $this->addressLine = $addressLine;

        return $this;
    }

    public function setBuildingNumber(string $buildingNumber): CustomerAddress
    {
        $this->buildingNumber = $buildingNumber;

        return $this;
    }

    public function setComplement(string $complement): CustomerAddress
    {
        $this->complement = $complement;

        return $this;
    }

    public function setNeighborhood(string $neighborhood): CustomerAddress
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    public function setCity(string $city): CustomerAddress
    {
        $this->city = $city;

        return $this;
    }

    public function setState(string $state): CustomerAddress
    {
        $this->state = $state;

        return $this;
    }

    public function setCountry(string $country): CustomerAddress
    {
        $this->country = $country;

        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getAddressLine(): string
    {
        return $this->addressLine;
    }

    public function getBuildingNumber(): string
    {
        return $this->buildingNumber;
    }

    public function getComplement(): string
    {
        return $this->complement;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

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
