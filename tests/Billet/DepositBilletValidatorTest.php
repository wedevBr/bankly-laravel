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
    public function testValidateAccountIsValidObject()
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
    public function testValidateAccountBranch()
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
    public function testValidateAccountNumber()
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
    public function testValidatePayerIsValidObject()
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
    public function testValidatePayerDocument()
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
    public function testValidatePayerName()
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
    public function testValidatePayerTradeName()
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
    public function testValidatePayerAddressIsValidObject()
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
    public function testValidatePayerAddressLine()
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
    public function testValidatePayerCityAddress()
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
    public function testValidatePayerStateAddress()
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
    public function testValidatePayerZipCodeAddress()
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
    public function testValidateAlias()
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
    public function testValidateDocumentNumber()
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
    public function testValidateAmount()
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
    public function testValidateAmountIsGreaterThanZero()
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
    public function testValidateDueDateIsValidDate()
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
    public function testValidateType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this type is not valid');
        $depositBillet = $this->validDepositBillet();
        $depositBillet->type = 'Withdraw';
        $depositBillet->validate();
    }
}
