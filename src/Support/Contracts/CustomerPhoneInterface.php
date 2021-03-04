<?php

namespace WeDevBr\Bankly\Support\Contracts;

/**
 * CustomePhoneInterface interface
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
interface CustomerPhoneInterface
{
    /**
     * @return string
     */
    public function getCountryCode(): string;

    /**
     * @return string
     */
    public function getNumber(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
