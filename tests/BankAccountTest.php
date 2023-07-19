<?php

/**
 * BankAccountTest class
 *
 * PHP version 7.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Adeildo Amorim <adeildo@capitaldigitalaberto.com.br>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/cda-admin-backend-laravel/
 */

namespace WeDevBr\Bankly\Tests;

use WeDevBr\Bankly\BankAccount;


class BankAccountTest extends TestCase
{
    private function validBankAccount(): BankAccount
    {
        $bankAccount = new BankAccount();
        $bankAccount->branch = '0001';
        $bankAccount->account = '1234552';
        $bankAccount->document = '01234567890';
        $bankAccount->name = 'Full name';
        return $bankAccount;
    }

    /**
     * @test
     */
    public function testInvalidBankBranch()
    {
        $this->expectException(\InvalidArgumentException::class);
        try{
            $bankAccount = $this->validBankAccount();
            $bankAccount->branch = null;
            $bankAccount->validate();
        } catch (\InvalidArgumentException $exception) {
            self::assertEquals('branch should be a numeric string', $exception->getMessage());
        }

    }

    /**
     * @test
     */
    public function testInvalidBankAccount()
    {
        $bankAccount = $this->validBankAccount();
        $bankAccount->account = null;
        $this->assertThrowableMessage('account should be a numeric string', fn() => $bankAccount->validate());
    }

    /**
     * @test
     */
    public function testInvalidBankDocument()
    {
        $bankAccount = $this->validBankAccount();
        $bankAccount->document = null;
        $this->assertThrowableMessage('document should be a string', fn() => $bankAccount->validate());

        $bankAccount = $this->validBankAccount();
        $bankAccount->document = '12345678901';

        $this->assertThrowableMessage('!!cpf_cnpj invalid', fn() => $bankAccount->validate());
    }

    /**
     * @test
     */
    public function testInvalidBankTDocument()
    {
        $bankAccount = $this->validBankAccount();
        $bankAccount->document = '12345678901';

        $this->assertThrowableMessage('!!cpf_cnpj invalid', fn() => $bankAccount->validate());
    }



    /**
     * @test
     */
    public function testInvalidBankName()
    {
        $this->expectException(\InvalidArgumentException::class);
        try{
            $bankAccount = $this->validBankAccount();
            $bankAccount->name = null;
            $bankAccount->validate();
        } catch (\InvalidArgumentException $exception) {
            $this->assertEquals('name should be a string', $exception->getMessage());
        }

    }

    /**
     * @test
     */
    public function testInvalidAccountType()
    {
        $this->expectException(\InvalidArgumentException::class);
        try {
            $bankAccount = $this->validBankAccount();
            $bankAccount->accountType = null;
            $bankAccount->validate();
        } catch (\InvalidArgumentException $exception) {
            $this->assertEquals('accountType should be one of them: CHECKING, SAVINGS', $exception->getMessage());
        }
    }
}
