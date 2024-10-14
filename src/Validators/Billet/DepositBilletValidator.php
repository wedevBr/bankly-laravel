<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\BankAccount;
use WeDevBr\Bankly\Types\Billet\DepositBillet;
use WeDevBr\Bankly\Types\Billet\Discount;
use WeDevBr\Bankly\Types\Billet\Fine;
use WeDevBr\Bankly\Types\Billet\Interest;
use WeDevBr\Bankly\Types\Billet\Payer;

/**
 * DepositBilletValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class DepositBilletValidator
{
    private DepositBillet $depositBillet;

    public function __construct(DepositBillet $depositBillet)
    {
        $this->depositBillet = $depositBillet;
    }

    /**
     * Validate the attributes of the deposit billet class
     */
    public function validate(): void
    {
        $this->validateAlias();
        $this->validateDocumentNumber();
        $this->validateAmount();
        $this->validateDueDate();
        $this->validateType();
        $this->validateBankAccount();
        $this->validatePayer();
        $this->validateClosePayment();
        $this->validateInterest();
        $this->validateFine();
        $this->validateDiscounts();
    }

    /**
     * This validates the alias
     *
     * @throws InvalidArgumentException
     */
    private function validateAlias(): void
    {
        $alias = $this->depositBillet->alias;
        if (empty($alias) || ! is_string($alias)) {
            throw new InvalidArgumentException('alias should be a string');
        }
    }

    /**
     * This validates the document number
     *
     * @throws InvalidArgumentException
     */
    private function validateDocumentNumber(): void
    {
        $documentNumber = $this->depositBillet->documentNumber;
        if (empty($documentNumber) || ! is_string($documentNumber) || ! is_numeric($documentNumber)) {
            throw new InvalidArgumentException('document number should be a numeric string');
        }
    }

    /**
     * This validates the amount
     *
     * @throws InvalidArgumentException
     */
    private function validateAmount(): void
    {
        $amount = $this->depositBillet->amount;
        if (empty($amount) || ! is_string($amount) || ! is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates the due date
     *
     * @throws InvalidArgumentException
     */
    private function validateDueDate(): void
    {
        $dueDate = $this->depositBillet->dueDate;

        try {
            $date = now()->createFromFormat('Y-m-d', $dueDate);
            if (! $date->gt(now())) {
                throw new InvalidArgumentException('due date must be greater than the current date');
            }
        } catch (\Throwable $th) {
            throw new InvalidArgumentException('due date should be a valid date');
        }
    }

    /**
     * This validates a type
     *
     * @throws InvalidArgumentException
     */
    private function validateType(): void
    {
        $type = $this->depositBillet->type;
        if (empty($type) || ! is_string($type)) {
            throw new InvalidArgumentException('type should be a string');
        }

        $types = ['Deposit', 'Levy'];
        if (! in_array($this->depositBillet->type, $types)) {
            throw new InvalidArgumentException('this type is not valid');
        }
    }

    /**
     * This validates a bank account
     *
     * @throws InvalidArgumentException
     */
    private function validateBankAccount(): void
    {
        if (! $this->depositBillet->account instanceof BankAccount) {
            throw new InvalidArgumentException('account should be a BankAccount type');
        }

        $this->depositBillet
            ->account
            ->validate();
    }

    /**
     * This validates the payer
     *
     * @throws InvalidArgumentException
     */
    private function validatePayer(): void
    {
        if (! $this->depositBillet->payer instanceof Payer) {
            throw new InvalidArgumentException('payer should be a Payer type');
        }

        $this->depositBillet
            ->payer
            ->validate();
    }

    /**
     * This validates the close payment date
     *
     * @throws InvalidArgumentException
     */
    private function validateClosePayment(): void
    {
        $closePayment = $this->depositBillet->closePayment;
        if (! empty($closePayment)) {
            try {
                $date = now()->createFromFormat('Y-m-d', $closePayment);
                if (! $date->gt(now())) {
                    throw new InvalidArgumentException('close payment date must be greater than the current date');
                }
            } catch (\Throwable $th) {
                throw new InvalidArgumentException('close payment date should be a valid date');
            }
        }
    }

    /**
     * This validates the interest
     *
     * @throws InvalidArgumentException
     */
    private function validateInterest(): void
    {
        if (! empty($this->depositBillet->interest)) {
            if (! $this->depositBillet->interest instanceof Interest) {
                throw new InvalidArgumentException('interest should be a Interest type');
            }
            $this->depositBillet->interest->validate();
        }
    }

    /**
     * This validates the fine
     *
     * @throws InvalidArgumentException
     */
    private function validateFine(): void
    {
        if (! empty($this->depositBillet->fine)) {
            if (! $this->depositBillet->fine instanceof Fine) {
                throw new InvalidArgumentException('fine should be a Fine type');
            }
            $this->depositBillet->fine->validate();
        }
    }

    /**
     * This validates the discounts
     *
     * @throws InvalidArgumentException
     */
    private function validateDiscounts(): void
    {
        if (! empty($this->depositBillet->discount)) {
            if (! $this->depositBillet->discount instanceof Discount) {
                throw new InvalidArgumentException('discounts should be a Discounts type');
            }
            $this->depositBillet->discount->validate();
        }
    }
}
