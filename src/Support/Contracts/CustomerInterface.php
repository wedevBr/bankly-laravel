<?php

namespace WeDevBr\Bankly\Support\Contracts;

use WeDevBr\Bankly\Inputs\CustomerAddress;
use WeDevBr\Bankly\Inputs\CustomerPhone;

/**
 * CustomerInterface interface
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
interface CustomerInterface
{
    /**
     * @return string
     */
    public function getRegisterName(): string;

    /**
     * @return string
     */
    public function getSocialName(): string;

    /**
     * @return CustomerPhone
     */
    public function getPhone(): CustomerPhone;

    /**
     * @return CustomerAddress
     */
    public function getAddress(): CustomerAddress;

    /**
     * @return string
     */
    public function getBirthDate(): string;

    /**
     * @return string
     */
    public function getMotherName(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
