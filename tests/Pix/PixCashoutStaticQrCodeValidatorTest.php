<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use stdClass;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\Bank;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashout;
use WeDevBr\Bankly\Types\Pix\PixCashoutManual;
use WeDevBr\Bankly\Types\Pix\PixCashoutStaticQrCode;

/**
 * PixCashoutValidatorTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
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
     * @return BankAccount
     */
    public function validRecipient()
    {
        $addressingAccount = new AddressingAccount();
        $addressingAccount->branch = '0002';
        $addressingAccount->number = '2222';
        $addressingAccount->type = 'CHECKING';

        $bank = new Bank();
        $bank->ispb = '00000000';
        $bank->compe = '001';
        $bank->name = 'Banco BB S.A.';

        $recipient = new BankAccount();
        $recipient->documentNumber = '12345678909';
        $recipient->name = 'Sara Smith';
        $recipient->account = $addressingAccount;
        $recipient->bank = $bank;

        return $recipient;
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
        $pixCashout->recipient = $this->validRecipient();
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
        $this->expectErrorMessage('amount should be a numeric string and greater than zero');
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
        $this->expectErrorMessage('amount should be a numeric string and greater than zero');
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
        $this->expectErrorMessage('cashout description should be a string');
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
        $this->expectErrorMessage('sender should be a BankAccount');
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
        $this->expectErrorMessage('branch should be a numeric string');
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
        $this->expectErrorMessage('number account should be a numeric string');
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
        $this->expectErrorMessage('type account should be a string');
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
        $this->expectErrorMessage('this account type is not valid');
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
        $this->expectErrorMessage('bank ispb should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->bank->ispb = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderBankCompe()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank compe account should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->bank->compe = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderBankName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank name should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->bank->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateSenderDocumentNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('document number should be a numeric string');
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
        $this->expectErrorMessage('name should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->sender->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('recipient should be a BankAccount');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient = new stdClass;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientAccountBranch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('branch should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->account->branch = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientAccountNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('number account should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->account->number = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientAccountTypeIsNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('type account should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->account->type = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientAccountTypeIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('this account type is not valid');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->account->type = 'CORRENTE';
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientBankIspb()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank ispb should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->bank->ispb = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientBankCompe()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank compe account should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->bank->compe = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientBankName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('bank name should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->bank->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientDocumentNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('document number should be a numeric string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->documentNumber = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateRecipientName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('name should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->recipient->name = null;
        $pixCashout->validate();
    }

    /**
     * @return void
     */
    public function testValidateInitializationTypeIsString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('initialization type should be a string');
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
        $this->expectErrorMessage('this initialization type is not valid');
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
        $this->expectErrorMessage('end to end id should be a string');
        $pixCashout = $this->validPixCashout();
        $pixCashout->endToEndId = null;
        $pixCashout->validate();
    }
}
