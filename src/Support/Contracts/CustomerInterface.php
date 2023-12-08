<?php

namespace WeDevBr\Bankly\Support\Contracts;

use Illuminate\Contracts\Support\Arrayable;
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
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
interface CustomerInterface extends Arrayable
{
    public function getRegisterName(): string;

    public function getSocialName(): string;

    public function getPhone(): CustomerPhone;

    public function getAddress(): CustomerAddress;

    public function getBirthDate(): string;

    public function getMotherName(): string;

    public function getEmail(): string;
}
