<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use stdClass;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\Bank;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashout;
use WeDevBr\Bankly\Types\Pix\PixCashoutManual;

/**
 * PixCashoutValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutManualValidatorTest extends TestCase
{
    /**
     * @return BankAccount
     */
    public function validSender()
    {
        $addressingAccount = new AddressingAccount;
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1111';
        $addressingAccount->type = 'CHECKING';

        $bank = new Bank;
        $bank->ispb = '11112222';
        $bank->compe = '323';
        $bank->name = 'Banco Test S.A.';

        $sender = new BankAccount;
        $sender->documentNumber = '12345678909';
        $sender->name = 'Jhon Smith';
        $sender->account = $addressingAccount;
        $sender->bank = $bank;

        return $sender;
    }

    /**
     * @return BankAccount
     */
    public function validRecipient()
    {
        $addressingAccount = new AddressingAccount;
        $addressingAccount->branch = '0002';
        $addressingAccount->number = '2222';
        $addressingAccount->type = 'CHECKING';

        $bank = new Bank;
        $bank->ispb = '00000000';
        $bank->compe = '001';
        $bank->name = 'Banco BB S.A.';

        $recipient = new BankAccount;
        $recipient->documentNumber = '12345678909';
        $recipient->name = 'Sara Smith';
        $recipient->account = $addressingAccount;
        $recipient->bank = $bank;

        return $recipient;
    }

    /**
     * @return PixCashout
     */
    public function validPixCashoutManual()
    {
        $pixCashout = new PixCashoutManual;
        $pixCashout->amount = '83.23';
        $pixCashout->description = 'Mercado';
        $pixCashout->sender = $this->validSender();
        $pixCashout->recipient = $this->validRecipient();
        $pixCashout->initializationType = 'Manual';

        return $pixCashout;
    }

    /**
     * @return void
     */
    public function test_validate_amount_is_numeric()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $pixCashout = $this->validPixCashoutManual();
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
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->amount = 0;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_description()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cashout description should be a string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->description = 0;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('sender should be a BankAccount');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender = new stdClass;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_account_branch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('branch should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->account->branch = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_account_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('number account should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->account->number = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_account_type_is_null()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type account should be a string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->account->type = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_account_type_is_valid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this account type is not valid');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->account->type = 'CORRENTE';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_bank_ispb()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('bank ispb should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->bank->ispb = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_document_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document number should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->documentNumber = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_sender_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name should be a string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->sender->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_object()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('recipient should be a BankAccount');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient = new stdClass;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_account_branch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('branch should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->account->branch = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_account_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('number account should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->account->number = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_account_type_is_null()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type account should be a string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->account->type = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_account_type_is_valid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this account type is not valid');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->account->type = 'CORRENTE';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_bank_ispb()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('bank ispb should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->bank->ispb = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_document_number()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document number should be a numeric string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->documentNumber = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_recipient_name()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name should be a string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->recipient->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_initialization_type_is_string()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('initialization type should be a string');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->initializationType = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function test_validate_initialization_type_is_valid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this initialization type is not valid');
        $pixCashout = $this->validPixCashoutManual();
        $pixCashout->initializationType = 'PIX';
        $pixCashout->validate();
    }
}
