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
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class Customer implements CustomerInterface
{
    /** @var string */
    protected string $registerName;

    /** @var ?string */
    protected ?string $socialName = null;

    /** @var CustomerPhone */
    protected CustomerPhone $phone;

    /** @var CustomerAddress */
    protected CustomerAddress $address;

    /** @var string */
    protected string $birthDate;

    /** @var string */
    protected string $motherName;

    /** @var string */
    protected string $email;

    /** @var string|null */
    protected ?string $pepLevel = null;

    /** @var string|null */
    protected ?string $occupation = null;

    /** @var string|null */
    protected ?string $assertedIncome = null;

    /** @var string|null */
    protected ?string $selfieToken = null;

    /** @var string|null */
    protected ?string $idCardFrontToken = null;

    /** @var string|null */
    protected ?string $idCardBackToken = null;

    protected ?string $currency = 'BRL';

    /**
     * @param string $name
     * @return Customer
     */
    public function setRegisterName(string $name): Customer
    {
        $this->registerName = $name;
        return $this;
    }

    /**
     * @param string $name
     * @return Customer
     */
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

    /**
     * @param string $birthDate
     * @return Customer
     */
    public function setBirthDate(string $birthDate): Customer
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @param string $motherName
     * @return Customer
     */
    public function setMotherName(string $motherName): Customer
    {
        $this->motherName = $motherName;
        return $this;
    }

    /**
     * @param string $email
     * @return Customer
     */
    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $pepLevel
     * @return Customer
     */
    public function setPepLevel(string $pepLevel): Customer
    {
        $this->pepLevel = $pepLevel;
        return $this;
    }

    /**
     * @param string $occupation
     * @return Customer
     */
    public function setOccupation(string $occupation): Customer
    {
        $this->occupation = $occupation;
        return $this;
    }

    /**
     * @param string $declaredIncome
     * @return Customer
     */
    public function setDeclaredIncome(string $declaredIncome): Customer
    {
        $this->declaredIncome = $declaredIncome;
        return $this;
    }

    /**
     * @param string $selfieToken
     * @return Customer
     */
    public function setSelfieToken(string $selfieToken): Customer
    {
        $this->selfieToken = $selfieToken;
        return $this;
    }

    /**
     * @param string $idCardFrontToken
     * @return Customer
     */
    public function setIdCardFrontToken(string $idCardFrontToken): Customer
    {
        $this->idCardFrontToken = $idCardFrontToken;
        return $this;
    }

    /**
     * @param string $idCardBackToken
     * @return Customer
     */
    public function setIdCardBackToken(string $idCardBackToken): Customer
    {
        $this->idCardBackToken = $idCardBackToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterName(): string
    {
        return $this->registerName;
    }

    /**
     * @return string
     */
    public function getSocialName(): string
    {
        return $this->socialName;
    }

    /**
     * @return CustomerPhone
     */
    public function getPhone(): CustomerPhone
    {
        return $this->phone;
    }

    /**
     * @return CustomerAddress
     */
    public function getAddress(): CustomerAddress
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getMotherName(): string
    {
        return $this->motherName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPepLevel(): string
    {
        return $this->pepLevel;
    }

    /**
     * @return string
     */
    public function getDeclaredIncome(): string
    {
        return $this->declaredIncome;
    }

    /**
     * @return string
     */
    public function getOccupation(): string
    {
        return $this->occupation;
    }

    /**
     * @return string
     */
    public function getSelfieToken(): string
    {
        return $this->selfieToken;
    }

    /**
     * @return string
     */
    public function getIdCardFrontToken(): string
    {
        return $this->idCardFrontToken;
    }

    /**
     * @return string
     */
    public function getIdCardBackToken(): string
    {
        return $this->idCardBackToken;
    }

    /**
     * @return array
     */
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
                'value' => $this->assertedIncome,
                'currency' => $this->currency
            ],
            'occupation' => $this->occupation,
            'pep' => [
                'level' => $this->pepLevel
            ],
            'documentation' => [
                'selfie' => $this->selfieToken,
                'idCardFront' => $this->idCardFrontToken,
                'idCardBack' => $this->idCardBackToken,
            ]
        ];
    }
}
