<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutManual;

/**
 * PixCashoutManualValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutManualValidator
{
    /** @var PixCashoutManual */
    private $pixCashoutManual;

    /**
     * @param PixCashoutManual $pixCashoutManual
     */
    public function __construct(PixCashoutManual $pixCashoutManual)
    {
        $this->pixCashoutManual = $pixCashoutManual;
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
    }

    /**
     * This validates the amount
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAmount()
    {
        $amount = $this->pixCashoutManual->amount;
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
        $description = $this->pixCashoutManual->description;
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
        if (!$this->pixCashoutManual->sender instanceof BankAccount) {
            throw new \InvalidArgumentException('sender should be a BankAccount');
        }

        $this->pixCashoutManual
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
        if (!$this->pixCashoutManual->recipient instanceof BankAccount) {
            throw new \InvalidArgumentException('recipient should be a BankAccount');
        }

        $this->pixCashoutManual
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
        $initializationType = $this->pixCashoutManual->initializationType;
        if (empty($initializationType) || !is_string($initializationType)) {
            throw new \InvalidArgumentException('initialization type should be a string');
        }

        if ($this->pixCashoutManual->initializationType != 'Manual') {
            throw new \InvalidArgumentException('this initialization type is not valid');
        }
    }
}
