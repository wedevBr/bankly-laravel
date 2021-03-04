<?php

/**
 * BanklyTest class
 *
 * PHP version 7.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Adeildo Amorim <adeildo@capitaldigitalaberto.com.br>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/cda-admin-backend-laravel/
 */

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Bankly;


class BanklyTest extends TestCase
{
    private function bankly()
    {
        return $this->mock(Bankly::class);
    }

    /**
     * @test
     */
    public function testEnsureMethodTransferExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'transfer')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetBankListExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getBankList')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetBalanceExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getBalance')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetStatementExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getStatement')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetEventsExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getEvents')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetTransferStatusExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getTransferStatus')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodSetApiVersionExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getTransferStatus')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetTransferFundsExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getTransferFunds')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodFindTransferFundByAuthCodeExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'findTransferFundByAuthCode')
        );
    }

    /**
     * @test
     */
    public function testEnsureMethodGetAccountExists()
    {
        $this->assertTrue(
            method_exists($this->bankly(), 'getAccount')
        );
    }
}
