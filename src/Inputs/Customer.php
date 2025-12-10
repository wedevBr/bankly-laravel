<?php

namespace WeDevBr\Bankly\Inputs;

use WeDevBr\Bankly\Support\Contracts\CustomerInterface;

/**
 * Customer class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Gabriel Oliveira <gabriel.oliveira@wedev.software>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class Customer implements CustomerInterface
{
    protected string $registerName;

    protected ?string $socialName = null;

    protected CustomerPhone $phone;

    protected CustomerAddress $address;

    protected string $birthDate;

    protected string $motherName;

    protected string $email;

    protected ?string $pepLevel = null;

    protected ?string $occupation = null;

    protected ?float $assertedIncome = null;

    protected ?string $selfieToken = null;

    protected ?string $idCardFrontToken = null;

    protected ?string $idCardBackToken = null;

    protected ?string $currency = 'BRL';

    protected ?bool $hasBrazilianNationality = false;

    public function setRegisterName(string $name): Customer
    {
        $this->registerName = $name;

        return $this;
    }

    public function setSocialName(string $name): Customer
    {
        $this->socialName = $name;

        return $this;
    }

    public function setPhone(CustomerPhone $phone): Customer
    {
        $this->phone = $phone;

        return $this;
    }

    public function setAddress(CustomerAddress $address): Customer
    {
        $this->address = $address;

        return $this;
    }

    public function setBirthDate(string $birthDate): Customer
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function setMotherName(string $motherName): Customer
    {
        $this->motherName = $motherName;

        return $this;
    }

    public function setEmail(string $email): Customer
    {
        $this->email = $email;

        return $this;
    }

    public function setPepLevel(string $pepLevel): Customer
    {
        $this->pepLevel = $pepLevel;

        return $this;
    }

    public function setOccupation(string $occupation): Customer
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function setAssertedIncome(float $assertedIncome): Customer
    {
        $this->assertedIncome = $assertedIncome;

        return $this;
    }

    public function setCurrencyIncome(string $currency = 'BRL'): Customer
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrencyIncome(): string
    {
        return $this->currency;
    }

    public function setSelfieToken(string $selfieToken): Customer
    {
        $this->selfieToken = $selfieToken;

        return $this;
    }

    public function setIdCardFrontToken(string $idCardFrontToken): Customer
    {
        $this->idCardFrontToken = $idCardFrontToken;

        return $this;
    }

    public function setIdCardBackToken(string $idCardBackToken): Customer
    {
        $this->idCardBackToken = $idCardBackToken;

        return $this;
    }

    public function setHasBrazilianNationality(bool $hasBrazilianNationality): Customer
    {
        $this->hasBrazilianNationality = $hasBrazilianNationality;

        return $this;
    }

    public function getRegisterName(): string
    {
        return $this->registerName;
    }

    public function getSocialName(): string
    {
        return $this->socialName;
    }

    public function getPhone(): CustomerPhone
    {
        return $this->phone;
    }

    public function getAddress(): CustomerAddress
    {
        return $this->address;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getMotherName(): string
    {
        return $this->motherName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPepLevel(): string
    {
        return $this->pepLevel;
    }

    public function getAssertedIncome(): string
    {
        return $this->assertedIncome;
    }

    public function getOccupation(): string
    {
        return $this->occupation;
    }

    public function getSelfieToken(): string
    {
        return $this->selfieToken;
    }

    public function getIdCardFrontToken(): string
    {
        return $this->idCardFrontToken;
    }

    public function getIdCardBackToken(): string
    {
        return $this->idCardBackToken;
    }

    public function getHasBrazilianNationality(): ?bool
    {
        return $this->hasBrazilianNationality;
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone->toArray(),
            'address' => $this->address->toArray(),
            'socialName' => $this->socialName,
            'registerName' => $this->registerName,
            'birthDate' => $this->birthDate,
            'motherName' => $this->motherName,
            'email' => $this->email,
            'assertedIncome' => [
                'value' => (string) $this->assertedIncome,
                'currency' => $this->currency,
            ],
            'occupation' => $this->occupation,
            'pep' => [
                'level' => $this->pepLevel,
            ],
            'documentation' => [
                'selfie' => $this->selfieToken,
                'idCardFront' => $this->idCardFrontToken,
                'idCardBack' => $this->idCardBackToken,
            ],
            'hasBrazilianNationality' => $this->hasBrazilianNationality,
        ];
    }
}
