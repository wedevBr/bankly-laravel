<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use stdClass;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutRefund;

/**
 * PixCashoutRefundValidatorTest class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutRefundValidatorTest extends TestCase
{
    /**
     * @return AddressingAccount
     */
    public function validAccount()
    {
        $addressingAccount = new AddressingAccount;
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1111';
        $addressingAccount->type = 'CHECKING';

        return $addressingAccount;
    }

    /**
     * @return PixCashoutRefund
     */
    public function validPixCashoutRefund()
    {
        $pixCashoutRefund = new PixCashoutRefund;
        $pixCashoutRefund->account = $this->validAccount();
        $pixCashoutRefund->authenticationCode = '79bb6e53-6869-42c6-be15-2dba237f306b';
        $pixCashoutRefund->amount = '83.23';
        $pixCashoutRefund->refundCode = 'SL02';
        $pixCashoutRefund->description = 'Devolução de Pix recebido';

        return $pixCashoutRefund;
    }

    /**
     * @return void
     */
    public function test_validate_account_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('account should be a AddressingAccount');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->account = new stdClass;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_account_branch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('branch should be a numeric string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->account->branch = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_account_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('number account should be a numeric string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->account->number = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_account_type_is_null()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type account should be a string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->account->type = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_account_type_is_valid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this account type is not valid');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->account->type = 'CORRENTE';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_authentication_code()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('authentication code should be a string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->authenticationCode = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_amount_is_numeric()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->amount = '2a.50';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_amount_is_greater_zero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->amount = 0;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_refund_code_is_string()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('refund code should be a string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->refundCode = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_refund_code_is_valid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this refund code is not valid');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->refundCode = '123';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_refund_reason()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('refund reason should be a string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->refundReason = 123;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_description()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('refund description should be a string');
        $pixCashout = $this->validPixCashoutRefund();
        $pixCashout->description = 123;
        $pixCashout->validate();
    }
}
