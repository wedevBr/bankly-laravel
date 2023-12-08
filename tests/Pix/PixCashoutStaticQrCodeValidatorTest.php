<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use stdClass;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\Bank;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutStaticQrCode;

/**
 * PixCashoutValidatorTest class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutStaticQrCodeValidatorTest extends TestCase
{
    /**
     * @return BankAccount
     */
    public function validSender()
    {
        $addressingAccount = new AddressingAccount();
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1111';
        $addressingAccount->type = 'CHECKING';

        $bank = new Bank();
        $bank->ispb = '11112222';
        $bank->compe = '323';
        $bank->name = 'Banco Test S.A.';

        $sender = new BankAccount();
        $sender->documentNumber = '12345678909';
        $sender->name = 'Jhon Smith';
        $sender->account = $addressingAccount;
        $sender->bank = $bank;

        return $sender;
    }

    /**
     * @return PixCashoutStaticQrCode
     */
    public function validPixCashout()
    {
        $pixCashout = new PixCashoutStaticQrCode();
        $pixCashout->amount = '83.23';
        $pixCashout->description = 'Mercado';
        $pixCashout->sender = $this->validSender();
        $pixCashout->initializationType = 'StaticQrCode';
        $pixCashout->endToEndId = 'fhoqiwuehf98adhsf89a7dhf9ahsdofhlasdhofa';

        return $pixCashout;
    }

    /**
     * @return void
     */
    public function testValidateAmountIsNumeric()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $pixCashout = $this->validPixCashout();
        $pixCashout->amount = '2a.50';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateAmountIsGreaterZero()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('amount should be a numeric string and greater than zero');
        $pixCashout = $this->validPixCashout();
        $pixCashout->amount = 0;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateDescription()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cashout description should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->description = 0;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('sender should be a BankAccount');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender = new stdClass;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderAccountBranch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('branch should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->account->branch = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderAccountNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('number account should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->account->number = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderAccountTypeIsNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type account should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->account->type = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderAccountTypeIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this account type is not valid');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->account->type = 'CORRENTE';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderBankIspb()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('bank ispb should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->bank->ispb = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderDocumentNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document number should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->documentNumber = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateInitializationTypeIsString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('initialization type should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->initializationType = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateInitializationTypeIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this initialization type is not valid');
        $pixCashout = $this->validPixCashout();
        $pixCashout->initializationType = 'PIX';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateEndToEndId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('end to end id should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->endToEndId = null;
        $pixCashout->validate();
    }
}
