<?php

namespace WeDevBr\Bankly\Support\Contracts;

/**
 * CustomerAddressInterface interface
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
interface CustomerAddressInterface
{
    /**
     * @return string
     */
    public function getZipCode(): string;

    /**
     * @return string
     */
    public function getAddressLine(): string;

    /**
     * @return string
     */
    public function getBuildingNumber(): string;

    /**
     * @return string
     */
    public function getComplement(): string;

    /**
     * @return string
     */
    public function getNeighborhood(): string;

    /**
     * @return string
     */
    public function getCity(): string;

    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @return string
     */
    public function getCountry(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}
