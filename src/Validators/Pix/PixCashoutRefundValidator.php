<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutRefund;

/**
 * PixCashoutRefundValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixCashoutRefundValidator
{
    /** @var PixCashoutRefund */
    private $pixCashoutRefund;

    /**
     * @param PixCashoutRefund $pixCashoutRefund
     */
    public function __construct(PixCashoutRefund $pixCashoutRefund)
    {
        $this->pixCashoutRefund = $pixCashoutRefund;
    }

    /**
     * Validate the attributes of the PIX cashout class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateAccount();
        $this->validateAuthenticationCode();
        $this->validateAmount();
        $this->validateRefundCode();
        $this->validateRefundReason();
        $this->validateDescription();
    }

    /**
     * This validates a sender bank account
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAccount()
    {
        if (!$this->pixCashoutRefund->account instanceof AddressingAccount) {
            throw new \InvalidArgumentException('account should be a AddressingAccount');
        }

        $this->pixCashoutRefund
            ->account
            ->validate();
    }

    /**
     * This validates authentication code
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAuthenticationCode()
    {
        $authenticationCode = $this->pixCashoutRefund->authenticationCode;
        if (empty($authenticationCode) || !is_string($authenticationCode)) {
            throw new \InvalidArgumentException('authentication code should be a string');
        }
    }

    /**
     * This validates the amount
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAmount()
    {
        $amount = $this->pixCashoutRefund->amount;
        if (empty($amount) || !is_string($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates the refund code
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateRefundCode()
    {
        $refundCode = $this->pixCashoutRefund->refundCode;
        if (empty($refundCode) || !is_string($refundCode)) {
            throw new \InvalidArgumentException('refund code should be a string');
        }

        $typeList = [
            'BE08',
            'FR01',
            'MD06',
            'SL02'
        ];
        if (!in_array($this->pixCashoutRefund->refundCode, $typeList)) {
            throw new \InvalidArgumentException('this refund code is not valid');
        }
    }

    /**
     * This validates the refund reason
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateRefundReason()
    {
        $refundReason = $this->pixCashoutRefund->refundReason;
        if (!empty($refundReason) && !is_string($refundReason)) {
            throw new \InvalidArgumentException('refund reason should be a string');
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
        $description = $this->pixCashoutRefund->description;
        if (!empty($description) && !is_string($description)) {
            throw new \InvalidArgumentException('refund description should be a string');
        }
    }
}