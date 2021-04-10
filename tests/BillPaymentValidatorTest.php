<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\BillPayment;

/**
 * BillPaymentValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BillPaymentValidatorTest extends TestCase
{
    /**
     * @return BillPayment
     */
    private function validBillPayment()
    {
        $billPayment = new BillPayment();
        $billPayment->bankBranch = '0001';
        $billPayment->bankAccount = '1122334455';
        $billPayment->amount = '466.99';
        $billPayment->id = 'AAA111BBB222CCC333DDD444';
        return $billPayment;
    }

    /**
     * @return void
     */
    public function testInvalidBillPaymentBranch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('branch should be a numeric string');
        $billPayment = $this->validBillPayment();
        $billPayment->bankBranch = null;
        $billPayment->validate();
    }

    /**
     * @return void
     */
    public function testInvalidBillPaymentAccount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('account should be a numeric string');

        $billPayment = $this->validBillPayment();
        $billPayment->bankAccount = null;
        $billPayment->validate();
    }

    /**
     * @return void
     */
    public function testInvalidBillPaymentAmount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('amount should be a numeric');
        $billPayment = $this->validBillPayment();
        $billPayment->amount = null;
        $billPayment->validate();
    }

    /**
     * @return void
     */
    public function testInvalidBillPaymentId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('ID should be a string');
        $billPayment = $this->validBillPayment();
        $billPayment->id = null;
        $billPayment->validate();
    }

    /**
     * @return void
     */
    public function testReturnConvertBillPaymentObjectToArray()
    {
        $array = $this->validBillPayment()->toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('bankBranch', $array);
        $this->assertArrayHasKey('bankAccount', $array);
        $this->assertArrayHasKey('amount', $array);
        $this->assertArrayHasKey('id', $array);
    }
}
