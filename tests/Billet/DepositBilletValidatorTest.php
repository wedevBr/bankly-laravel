<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;
use stdClass;
use WeDevBr\Bankly\Types\Billet\Address;
use WeDevBr\Bankly\Types\Billet\BankAccount;
use WeDevBr\Bankly\Types\Billet\DepositBillet;
use WeDevBr\Bankly\Types\Billet\Payer;

/**
 * DepositBilletValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class DepositBilletValidatorTest extends TestCase
{
    use WithFaker;

    /**
     * @return DepositBillet
     */
    public function validDepositBillet()
    {
        $bankAccount = new BankAccount;
        $bankAccount->branch = '0001';
        $bankAccount->number = '1234';

        $address = new Address;
        $address->addressLine = 'address';
        $address->city = 'city';
        $address->state = 'state';
        $address->zipCode = '36555000';

        $payer = new Payer;
        $payer->document = '12345678909';
        $payer->name = 'Jhon Smith';
        $payer->tradeName = 'Jhon Smith';
        $payer->address = $address;

        $depositBillet = new DepositBillet;
        $depositBillet->account = $bankAccount;
        $depositBillet->payer = $payer;
        $depositBillet->alias = 'Deposit Billet';
        $depositBillet->documentNumber = '12345678909';
        $depositBillet->amount = '69.99';
        $depositBillet->dueDate = now()->addDay()->format('Y-m-d');
        $depositBillet->type = 'Deposit';

        return $depositBillet;
    }

    /**
     * @return void
     */
    public function test_validate_account_is_valid_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('account should be a BankAccount type');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->account = new stdClass;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_account_branch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('branch should be a numeric string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->account->branch = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_account_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('bank account number should be a numeric string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->account->number = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_is_valid_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('payer should be a Payer type');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer = new stdClass;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_document()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('payer document should be a numeric string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->document = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('payer name should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->name = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_trade_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('payer trade name should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->tradeName = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_address_is_valid_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('payer address should be a Address type');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->address = new stdClass;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_address_line()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('address line should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->address->addressLine = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_city_address()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('city address should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->address->city = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_state_address()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('state address should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->address->state = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_payer_zip_code_address()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('zip code should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->payer->address->zipCode = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_alias()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('alias should be a string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->alias = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_document_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document number should be a numeric string');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->documentNumber = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_amount()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->amount = null;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_amount_is_greater_than_zero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->amount = 0;
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_due_date_is_valid_date()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('due date should be a valid date');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->dueDate = now()->format('Y-m-d');
        $depositBillet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_type()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this type is not valid');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->type = 'Withdraw';
        $depositBillet->validate();
    }
}
