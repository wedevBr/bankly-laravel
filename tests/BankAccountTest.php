<?php

/**
 * BankAccountTest class
 *
 * PHP version 7.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Adeildo Amorim <adeildo@capitaldigitalaberto.com.br>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/cda-admin-backend-laravel/
 */

namespace WeDevBr\Bankly\Tests;

use WeDevBr\Bankly\BankAccount;

class BankAccountTest extends TestCase
{
    private function validBankAccount(): BankAccount
    {
        $bankAccount = new BankAccount;
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
        $this->expectExceptionMessage('branch should be a numeric string');
        $bankAccount = $this->validBankAccount();
        $bankAccount->branch = null;
        $bankAccount->validate();
    }

    /**
     * @test
     */
    public function testInvalidBankAccount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('account should be a numeric string');
        $bankAccount = $this->validBankAccount();
        $bankAccount->account = null;
        $bankAccount->validate();
    }

    /**
     * @test
     */
    public function testInvalidBankDocument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document should be a string');
        $bankAccount = $this->validBankAccount();
        $bankAccount->document = null;
        $bankAccount->validate();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cpf_cnpj invalid');
        $bankAccount = $this->validBankAccount();
        $bankAccount->document = '12345678901';

    }

    /**
     * @test
     */
    public function testInvalidBankName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name should be a string');
        $bankAccount = $this->validBankAccount();
        $bankAccount->name = null;
        $bankAccount->validate();
    }

    /**
     * @test
     */
    public function testInvalidAccountType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('accountType should be one of them: CHECKING, SAVINGS');
        $bankAccount = $this->validBankAccount();
        $bankAccount->accountType = null;
        $bankAccount->validate();
    }
}
