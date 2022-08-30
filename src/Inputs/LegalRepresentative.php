<?php

namespace WeDevBr\Bankly\Inputs;

/**
 * Business Customer class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <marco.santos@wedev.softwarem>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class LegalRepresentative
{
    /** @var string */
    public $documentNumber;

    /** @var string */
    public $registerName;

    /** @var string */
    public $socialName;

    /** @var CustomerPhone */
    public $phone;

    /** @var CustomerAddress */
    public $address;

    /** @var string */
    public $birthDate;

    /** @var string */
    public $motherName;

    /** @var string */
    public $email;

    /** @var bool */
    public $isPoliticallyExposedPerson;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->phone = $this->phone->toArray();
        $this->address = $this->address->toArray();

        return (array) $this;
    }
}