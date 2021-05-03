<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashout;

/**
 * PixCashoutValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutValidator
{
    /** @var PixCashout */
    private $pixCashout;

    /**
     * @param PixCashout $pixCashout
     */
    public function __construct(PixCashout $pixCashout)
    {
        $this->pixCashout = $pixCashout;
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
        $this->validateAddressKey();
        $this->validateReceiverReconciliationId();
        $this->validateEndToEndId();
    }

    /**
     * This validates the amount
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAmount()
    {
        $amount = $this->pixCashout->amount;
        if (empty($amount) || !is_string($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates a description
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateDescription()
    {
        $description = $this->pixCashout->description;
        if (empty($description) || !is_string($description)) {
            throw new \InvalidArgumentException('cashout description should be a string');
        }
    }

    /**
     * This validates a sender bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateSender()
    {
        if (!$this->pixCashout->sender instanceof BankAccount) {
            throw new \InvalidArgumentException('sender should be a BankAccount');
        }

        $this->pixCashout
            ->sender
            ->validate();
    }

    /**
     * This validates a recipient bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateRecipient()
    {
        if (!$this->pixCashout->recipient instanceof BankAccount) {
            throw new \InvalidArgumentException('recipient should be a BankAccount');
        }

        $this->pixCashout
            ->recipient
            ->validate();
    }

    /**
     * This validates a initialization type
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateInitializationType()
    {
        $initializationType = $this->pixCashout->initializationType;
        if (empty($initializationType) || !is_string($initializationType)) {
            throw new \InvalidArgumentException('initialization type should be a string');
        }

        $types = ['Manual', 'Key', 'StaticQrCode', 'DynamicQrCode'];
        if (!in_array($this->pixCashout->initializationType, $types)) {
            throw new \InvalidArgumentException('this initialization type is not valid');
        }
    }

    /**
     * This validates the address key
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAddressKey()
    {
        $addressKey = $this->pixCashout->addressKey;
        if (empty($addressKey) || !is_string($addressKey)) {
            throw new \InvalidArgumentException('address key should be a string');
        }
    }

    /**
     * This validates the receiver reconciliation id
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateReceiverReconciliationId()
    {
        $receiverReconciliationId = $this->pixCashout->receiverReconciliationId;
        if (empty($receiverReconciliationId) || !is_string($receiverReconciliationId)) {
            throw new \InvalidArgumentException('receiver reconciliation id should be a string');
        }
    }

    /**
     * This validates the end to end id
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateEndToEndId()
    {
        $endToEndId = $this->pixCashout->endToEndId;
        if (empty($endToEndId) || !is_string($endToEndId)) {
            throw new \InvalidArgumentException('end to end id should be a string');
        }
    }
}
