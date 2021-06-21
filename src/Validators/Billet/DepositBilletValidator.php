<?php

namespace WeDevBr\Bankly\Validators\Billet;

use WeDevBr\Bankly\Types\Billet\BankAccount;
use WeDevBr\Bankly\Types\Billet\DepositBillet;
use WeDevBr\Bankly\Types\Billet\Payer;

/**
 * DepositBilletValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class DepositBilletValidator
{
    /** @var DepositBillet */
    private $depositBillet;

    /**
     * @param DepositBillet $depositBillet
     */
    public function __construct(DepositBillet $depositBillet)
    {
        $this->depositBillet = $depositBillet;
    }

    /**
     * Validate the attributes of the deposit billet class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateAlias();
        $this->validateDocumentNumber();
        $this->validateAmount();
        $this->validateDueDate();
        $this->validateEmissionFee();
        $this->validateType();
        $this->validateBankAccount();
        $this->validatePayer();
    }

    /**
     * This validates the alias
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAlias()
    {
        $alias = $this->depositBillet->alias;
        if (empty($alias) || !is_string($alias)) {
            throw new \InvalidArgumentException('alias should be a string');
        }
    }

    /**
     * This validates the document number
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateDocumentNumber()
    {
        $documentNumber = $this->depositBillet->documentNumber;
        if (empty($documentNumber) || !is_string($documentNumber) || !is_numeric($documentNumber)) {
            throw new \InvalidArgumentException('document number should be a numeric string');
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
        $amount = $this->depositBillet->amount;
        if (empty($amount) || !is_string($amount) || !is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('amount should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates the due date
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateDueDate()
    {
        $dueDate = $this->depositBillet->dueDate;

        try {
            $date = now()->createFromFormat('Y-m-d', $dueDate);
            if (!$date->gt(now())) {
                throw new \InvalidArgumentException('due date must be greater than the current date');
            }
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException('due date should be a valid date');
        }
    }

    /**
     * This validates the emission fee
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateEmissionFee()
    {
        $emissionFee = $this->depositBillet->emissionFee;

        if (!is_bool($emissionFee)) {
            throw new \InvalidArgumentException('emission fee should be a boolean');
        }
    }

    /**
     * This validates a type
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateType()
    {
        $type = $this->depositBillet->type;
        if (empty($type) || !is_string($type)) {
            throw new \InvalidArgumentException('type should be a string');
        }

        $types = ['Deposit', 'Levy'];
        if (!in_array($this->depositBillet->type, $types)) {
            throw new \InvalidArgumentException('this type is not valid');
        }
    }

    /**
     * This validates a bank account
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateBankAccount()
    {
        if (!$this->depositBillet->account instanceof BankAccount) {
            throw new \InvalidArgumentException('account should be a BankAccount type');
        }

        $this->depositBillet
            ->account
            ->validate();
    }

    /**
     * This validates a bank account
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validatePayer()
    {
        if (!$this->depositBillet->payer instanceof Payer) {
            throw new \InvalidArgumentException('payer should be a Payer type');
        }

        $this->depositBillet
            ->payer
            ->validate();
    }
}
