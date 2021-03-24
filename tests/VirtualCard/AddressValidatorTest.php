<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Types\VirtualCard\Address;

/**
 * AddressValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class AddressValidatorTest extends TestCase
{
    /**
     * @return Address
     */
    private function validAddress()
    {
        $address = new Address();
        $address->zipCode = '29155909';
        $address->address = 'Rua Olegário Maciel';
        $address->number = '333';
        $address->neighborhood = 'Centro';
        $address->city = 'Vila Velha';
        $address->state = 'ES';
        $address->country = 'BR';

        return $address;
    }

    /**
     * @return void
     */
    public function testValidateZipCode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('zip code should be a numeric string');
        $address = $this->validAddress();
        $address->zipCode = null;
        $address->validate();
    }

    /**
     * @return void
     */
    public function testValidateAddress()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('address should be a string');
        $address = $this->validAddress();
        $address->address = null;
        $address->validate();
    }

    /**
     * @return void
     */
    public function testValidateNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('number should be a numeric string');
        $address = $this->validAddress();
        $address->number = 'null';
        $address->validate();
    }

    /**
     * @return void
     */
    public function testValidateNeighborhood()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('neighborhood should be a string');
        $address = $this->validAddress();
        $address->neighborhood = null;
        $address->validate();
    }

    /**
     * @return void
     */
    public function testValidateCity()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('city should be a string');
        $address = $this->validAddress();
        $address->city = null;
        $address->validate();
    }

    /**
     * @return void
     */
    public function testValidateState()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('state should be a string');
        $address = $this->validAddress();
        $address->state = null;
        $address->validate();
    }

    /**
     * @return void
     */
    public function testValidateCountry()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('country should be a string');
        $address = $this->validAddress();
        $address->country = '';
        $address->validate();
    }

    /**
     * @return void
     */
    public function testConvertVirtualCardObjectToArray()
    {
        $address = $this->validAddress();
        $array = $address->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('zipCode', $array);
        $this->assertArrayHasKey('address', $array);
        $this->assertArrayHasKey('number', $array);
        $this->assertArrayHasKey('neighborhood', $array);
        $this->assertArrayHasKey('complement', $array);
        $this->assertArrayHasKey('city', $array);
        $this->assertArrayHasKey('state', $array);
        $this->assertArrayHasKey('country', $array);
    }
}
