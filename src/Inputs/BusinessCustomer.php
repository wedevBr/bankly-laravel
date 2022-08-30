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
class BusinessCustomer
{
    /** @var string */
    public $businessName;

    /** @var string */
    public $tradingName;

    /** @var string */
    public $businessEmail;

    /** @var string */
    public $businessType;

    /** @var string */
    public $businessSize;

    /** @var CustomerAddress */
    public $businessAddress;

    /** @var LegalRepresentative */
    public $legalRepresentative;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->businessAddress = $this->businessAddress->toArray();
        $this->legalRepresentative = $this->legalRepresentative->toArray();

        return (array) $this;
    }
}