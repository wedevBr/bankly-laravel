<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutManual;

/**
 * PixCashoutManualValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutManualValidator
{
    private PixCashoutManual $pixCashoutManual;

    public function __construct(PixCashoutManual $pixCashoutManual)
    {
        $this->pixCashoutManual = $pixCashoutManual;
    }

    /**
     * Validate the attributes of the PIX cashout class
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
     * @throws InvalidArgumentException
     */
    private function validateAmount(): void
    {
        $amount = $this->pixCashoutManual->amount;
        if (empty($amount) || ! is_string($amount) || ! is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates a description
     *
     * @throws InvalidArgumentException
     */
    private function validateDescription(): void
    {
        $description = $this->pixCashoutManual->description;
        if (empty($description) || ! is_string($description)) {
            throw new InvalidArgumentException('cashout description should be a string');
        }
    }

    /**
     * This validates a sender bank account
     *
     * @throws InvalidArgumentException
     */
    private function validateSender(): void
    {
        if (! $this->pixCashoutManual->sender instanceof BankAccount) {
            throw new InvalidArgumentException('sender should be a BankAccount');
        }

        $this->pixCashoutManual
            ->sender
            ->validate();
    }

    /**
     * This validates a recipient bank account
     *
     * @throws InvalidArgumentException
     */
    private function validateRecipient(): void
    {
        if (! $this->pixCashoutManual->recipient instanceof BankAccount) {
            throw new InvalidArgumentException('recipient should be a BankAccount');
        }

        $this->pixCashoutManual
            ->recipient
            ->validate();
    }

    /**
     * This validates a initialization type
     *
     * @throws InvalidArgumentException
     */
    private function validateInitializationType(): void
    {
        $initializationType = $this->pixCashoutManual->initializationType;
        if (empty($initializationType) || ! is_string($initializationType)) {
            throw new InvalidArgumentException('initialization type should be a string');
        }

        if ($this->pixCashoutManual->initializationType != 'Manual') {
            throw new InvalidArgumentException('this initialization type is not valid');
        }
    }
}
