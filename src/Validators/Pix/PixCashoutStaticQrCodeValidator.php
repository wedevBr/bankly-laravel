<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutStaticQrCode;

/**
 * PixCashoutStaticQrCodeValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutStaticQrCodeValidator
{
    /** @var PixCashoutStaticQrCode */
    private $pixCashoutStaticQrCode;

    /**
     * @param PixCashoutStaticQrCode $pixCashoutStaticQrCode
     */
    public function __construct(PixCashoutStaticQrCode $pixCashoutStaticQrCode)
    {
        $this->pixCashoutStaticQrCode = $pixCashoutStaticQrCode;
    }

    /**
     * Validate the attributes of the PIX cashout class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateAmount();
        $this->validateDescription();
        $this->validateSender();
        $this->validateRecipient();
        $this->validateInitializationType();
        $this->validateEndToEndId();
    }

    /**
     * This validates the amount
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAmount()
    {
        $amount = $this->pixCashoutStaticQrCode->amount;
        if (empty($amount) || !is_string($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates a description
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateDescription()
    {
        $description = $this->pixCashoutStaticQrCode->description;
        if (empty($description) || !is_string($description)) {
            throw new \InvalidArgumentException('cashout description should be a string');
        }
    }

    /**
     * This validates a sender bank account
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateSender()
    {
        if (!$this->pixCashoutStaticQrCode->sender instanceof BankAccount) {
            throw new \InvalidArgumentException('sender should be a BankAccount');
        }

        $this->pixCashoutStaticQrCode
            ->sender
            ->validate();
    }

    /**
     * This validates a recipient bank account
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateRecipient()
    {
        if (!$this->pixCashoutStaticQrCode->recipient instanceof BankAccount) {
            throw new \InvalidArgumentException('recipient should be a BankAccount');
        }

        $this->pixCashoutStaticQrCode
            ->recipient
            ->validate();
    }

    /**
     * This validates a initialization type
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateInitializationType()
    {
        $initializationType = $this->pixCashoutStaticQrCode->initializationType;
        if (empty($initializationType) || !is_string($initializationType)) {
            throw new \InvalidArgumentException('initialization type should be a string');
        }

        if ($this->pixCashoutStaticQrCode->initializationType != 'StaticQrCode') {
            throw new \InvalidArgumentException('this initialization type is not valid');
        }
    }

    /**
     * This validates the end to end id
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateEndToEndId()
    {
        $endToEndId = $this->pixCashoutStaticQrCode->endToEndId;
        if (empty($endToEndId) || !is_string($endToEndId)) {
            throw new \InvalidArgumentException('end to end id should be a string');
        }
    }
}
