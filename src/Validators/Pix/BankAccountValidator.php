<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\BankAccount;

/**
 * BankAccountValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BankAccountValidator
{
    private BankAccount $bankAccount;

    public function __construct(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * Validate the attributes of the bank account class
     */
    public function validate(): void
    {
        $this->validateAccount();
        $this->validateBank();
        $this->validateDocumentNumber();
        $this->validateName();
    }

    /**
     * This validates a account
     *
     * @throws InvalidArgumentException
     */
    private function validateAccount(): void
    {
        $this->bankAccount->account->validate();
    }

    /**
     * This validates a bank
     *
     * @throws InvalidArgumentException
     */
    private function validateBank(): void
    {
        $this->bankAccount->bank->validate();
    }

    /**
     * This validates a document number
     *
     * @throws InvalidArgumentException
     */
    private function validateDocumentNumber(): void
    {
        $documentNumber = $this->bankAccount->documentNumber;
        if (empty($documentNumber) || ! is_string($documentNumber) || ! is_numeric($documentNumber)) {
            throw new InvalidArgumentException('document number should be a numeric string');
        }
    }

    /**
     * This validates a name
     *
     * @throws InvalidArgumentException
     */
    private function validateName(): void
    {
        $name = $this->bankAccount->name;
        if (empty($name) || ! is_string($name)) {
            throw new InvalidArgumentException('name should be a string');
        }
    }
}
