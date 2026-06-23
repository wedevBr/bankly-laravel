<?php

/**
 * BanklyTest class
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

use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\Bankly;

class BanklyTest extends TestCase
{
    private function bankly()
    {
        return $this->mock(Bankly::class);
    }

    #[Test]
    public function test_ensure_method_transfer_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'transfer')
        );
    }

    #[Test]
    public function test_ensure_method_get_bank_list_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getBankList')
        );
    }

    #[Test]
    public function test_ensure_method_get_balance_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getBalance')
        );
    }

    #[Test]
    public function test_ensure_method_get_statement_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getStatement')
        );
    }

    #[Test]
    public function test_ensure_method_get_events_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getEvents')
        );
    }

    #[Test]
    public function test_ensure_method_get_transfer_status_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getTransferStatus')
        );
    }

    #[Test]
    public function test_ensure_method_set_api_version_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getTransferStatus')
        );
    }

    #[Test]
    public function test_ensure_method_get_transfer_funds_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getTransferFunds')
        );
    }

    #[Test]
    public function test_ensure_method_find_transfer_fund_by_auth_code_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'findTransferFundByAuthCode')
        );
    }

    #[Test]
    public function test_ensure_method_get_account_exists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getAccount')
        );
    }
}
