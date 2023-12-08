<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Types\Card\Address;
use WeDevBr\Bankly\Types\Card\Card;

/**
 * CardValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class CardValidatorTest extends TestCase
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

    private function validVirtualCard(): Card
    {
        $virtualCard = new Card();
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
        $this->expectExceptionMessage('document number should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->documentNumber = null;
        $virtualCard->validate();

        $this->expectExceptionMessage('cpf_cnpj invalid');
        $virtualCard->document = '12345678901';
    }

    /**
     * @return void
     */
    public function testValidateCardName()
    {
        $this->expectExceptionMessage('card name should be a string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->cardName = null;
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateAlias()
    {
        $this->expectExceptionMessage('alias should be a string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->alias = '';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateBankAgency()
    {
        $this->expectExceptionMessage('bank agency should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->bankAgency = '12345';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateBankAccount()
    {
        $this->expectExceptionMessage('bank account should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->bankAccount = 'accountnumber';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidatePassword()
    {
        $this->expectExceptionMessage('password should be a numeric string');
        $virtualCard = $this->validVirtualCard();
        $virtualCard->password = 'A123';
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateAddress()
    {
        $this->expectExceptionMessage('zip code should be a numeric string');
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
