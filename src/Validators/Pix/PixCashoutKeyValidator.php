<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutKey;

/**
 * PixCashoutKeyValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutKeyValidator
{
    /** @var PixCashoutKey */
    private $pixCashoutKey;

    /**
     * @param PixCashoutKey $pixCashoutKey
     */
    public function __construct(PixCashoutKey $pixCashoutKey)
    {
        $this->pixCashoutKey = $pixCashoutKey;
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
        $this->validateInitializationType();
        $this->validateEndToEndId();
    }

    /**
     * This validates the amount
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAmount(): void
    {
        $amount = $this->pixCashoutKey->amount;
        if (empty($amount) || !is_string($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates a description
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateDescription(): void
    {
        $description = $this->pixCashoutKey->description;
        if (empty($description) || !is_string($description)) {
            throw new InvalidArgumentException('cashout description should be a string');
        }
    }

    /**
     * This validates a sender bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateSender(): void
    {
        if (!$this->pixCashoutKey->sender instanceof BankAccount) {
            throw new InvalidArgumentException('sender should be a BankAccount');
        }

        $this->pixCashoutKey
            ->sender
            ->validate();
    }

    /**
     * This validates a initialization type
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateInitializationType(): void
    {
        $initializationType = $this->pixCashoutKey->initializationType;
        if (empty($initializationType) || !is_string($initializationType)) {
            throw new InvalidArgumentException('initialization type should be a string');
        }

        if ($this->pixCashoutKey->initializationType != 'Key') {
            throw new InvalidArgumentException('this initialization type is not valid');
        }
    }

    /**
     * This validates the end to end id
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateEndToEndId(): void
    {
        $endToEndId = $this->pixCashoutKey->endToEndId;
        if (empty($endToEndId) || !is_string($endToEndId)) {
            throw new InvalidArgumentException('end to end id should be a string');
        }
    }
}
