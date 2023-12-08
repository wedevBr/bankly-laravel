<?php

namespace WeDevBr\Bankly\Support\Contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * CustomerAddressInterface interface
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
interface CustomerAddressInterface extends Arrayable
{
    public function getZipCode(): string;

    public function getAddressLine(): string;

    public function getBuildingNumber(): string;

    public function getComplement(): string;

    public function getNeighborhood(): string;

    public function getCity(): string;

    public function getState(): string;

    public function getCountry(): string;
}
