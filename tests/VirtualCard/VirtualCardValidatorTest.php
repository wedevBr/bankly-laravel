<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Types\VirtualCard\Address;
use WeDevBr\Bankly\Types\VirtualCard\VirtualCard;

/**
 * VirtualCardValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class VirtualCardValidatorTest extends TestCase
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
     * @return VirtualCard
     */
    private function validVirtualCard()
    {
        $virtualCard = new VirtualCard();
        $virtualCard->documentNumber = '01234567890';
        $virtualCard->cardName = 'Carla Dias';
        $virtualCard->alias = 'Carlinha';
        $virtualCard->bankAgency = '0001';
        $virtualCard->bankAccount = '11223344';
        $virtualCard->password = '1234';
        $virtualCard->address = $this->validAddress();

        return $virtualCard;
    }

    /**
     * @return void
     */
    public function testValidateDocumentNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('document number should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->documentNumber = null;
        $virtualCard->validate();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('cpf_cnpj invalid');
        $virtualCard->document = '12345678901';
    }

    /**
     * @return void
     */
    public function testValidateCardName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('card name should be a string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->cardName = null;
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateAlias()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('alias should be a string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->alias = '';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateBankAgency()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank agency should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->bankAgency = '12345';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateBankAccount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank account should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->bankAccount = 'accountnumber';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateProgramId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('program ID should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->programId = '2370021007715354237002100771535';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidatePassword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('password should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->password = 'A123';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateAddress()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('zip code should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->address->zipCode = '';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testConvertVirtualCardObjectToArray()
    {
        $virtualCard = $this->validVirtualCard();
        $array = $virtualCard->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('documentNumber', $array);
        $this->assertArrayHasKey('cardName', $array);
        $this->assertArrayHasKey('alias', $array);
        $this->assertArrayHasKey('bankAgency', $array);
        $this->assertArrayHasKey('bankAccount', $array);
        $this->assertArrayHasKey('programId', $array);
        $this->assertArrayHasKey('password', $array);
        $this->assertArrayHasKey('address', $array);
    }
}
