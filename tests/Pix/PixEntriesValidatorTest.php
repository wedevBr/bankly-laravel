<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\Types\Pix\PixEntries;

/**
 * PixEntriesValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixEntriesValidatorTest extends TestCase
{
    /**
     * @return PixEntries
     */
    public function validPixEntries()
    {
        $addressingKey = new AddressingKey();
        $addressingKey->type = 'CPF';
        $addressingKey->value = '12345678909';

        $addressingAccount = new AddressingAccount();
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1234';
        $addressingAccount->type = 'CHECKING';

        $pixEntries = new PixEntries();
        $pixEntries->addressingKey = $addressingKey;
        $pixEntries->account = $addressingAccount;

        return $pixEntries;
    }

    /**
     * @return void
     */
    public function testValidateAccountTypeIfIsString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('type account should be a string');
        $pixEntries = $this->validPixEntries();
        $pixEntries->account->type = null;
        $pixEntries->validate();
    }

    /**
     * @return void
     */
    public function testValidateAccountTypeIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('this account type is not valid');
        $pixEntries = $this->validPixEntries();
        $pixEntries->account->type = 'ANY';
        $pixEntries->validate();
    }

    /**
     * @return void
     */
    public function testValidateAccountNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('number account should be a numeric string');
        $pixEntries = $this->validPixEntries();
        $pixEntries->account->number = '1002A';
        $pixEntries->validate();
    }

    /**
     * @return void
     */
    public function testValidateAccountBranch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('branch should be a numeric string');
        $pixEntries = $this->validPixEntries();
        $pixEntries->account->branch = '0001-2';
        $pixEntries->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfAddressingKeyTypeIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('this key type is not valid');
        $pixEntries = $this->validPixEntries();
        $pixEntries->addressingKey->type = 'RG';
        $pixEntries->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfAddressingKeyTypeIsString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('type should be a string');
        $pixEntries = $this->validPixEntries();
        $pixEntries->addressingKey->type = null;
        $pixEntries->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfAddressingKeyValueIsString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('value should be a string');
        $pixEntries = $this->validPixEntries();
        $pixEntries->addressingKey->value = null;
        $pixEntries->validate();
    }
}
