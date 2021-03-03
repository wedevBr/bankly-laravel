<?php

namespace WeDevBr\Bankly\Inputs;

use WeDevBr\Bankly\Support\Contracts\CustomerInterface;

/**
 * Customer class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class Customer implements CustomerInterface
{
    /** @var string */
    protected $registerName;

    /** @var string */
    protected $socialName;

    /** @var CustomerPhone */
    protected $phone;

    /** @var CustomerAddress */
    protected $address;

    /** @var string */
    protected $birthDate;

    /** @var string */
    protected $motherName;

    /** @var string */
    protected $email;

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
        ];
    }
}
