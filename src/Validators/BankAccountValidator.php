<?php

/**
 * BankAccountValidator class
 *
 * PHP version 7.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Adeildo Amorim <adeildo@capitaldigitalaberto.com.br>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/cda-admin-backend-laravel/
 */

namespace WeDevBr\Bankly\Validators;

use InvalidArgumentException;
use WeDevBr\Bankly\BankAccount;

class BankAccountValidator
{
    private BankAccount $bank_account;

    /**
     * BankAccountValidator constructor.
     */
    public function __construct(BankAccount $bankAccount)
    {
        $this->bank_account = $bankAccount;
    }

    /**
     * This validate the bank account
     */
    public function validate(): void
    {
        $this->validateBranch();
        $this->validateAccount();
        $this->validateDocument();
        $this->validateName();
        $this->validateAccountType();
    }

    /**
     * This validate a bank branch
     *
     * @throws InvalidArgumentException
     */
    private function validateBranch(): void
    {
        $branch = $this->bank_account->branch;
        if (is_null($branch) || ! is_string($branch) || ! is_numeric($branch)) {
            throw new InvalidArgumentException('branch should be a numeric string');
        }
    }

    /**
     * This validate a bank account
     *
     * @throws InvalidArgumentException
     */
    private function validateAccount(): void
    {
        $account = $this->bank_account->account;
        if (is_null($account) || ! is_string($account) || ! is_numeric($account)) {
            throw new InvalidArgumentException('account should be a numeric string');
        }
    }

    /**
     * This validates a cpf_cnpj
     *
     * @throws InvalidArgumentException
     */
    private function validateDocument(): void
    {
        $document = $this->bank_account->document;
        if (is_null($document) || ! is_string($document)) {
            throw new InvalidArgumentException('document should be a string');
        }
        $documentValidator = new CpfCnpjValidator($document);
        $documentValidator->validate();
    }

    /**
     * This validates a given name
     *
     * @throws InvalidArgumentException
     */
    private function validateName(): void
    {
        $name = $this->bank_account->name;
        if (is_null($name) || ! is_string($name)) {
            throw new InvalidArgumentException('name should be a string');
        }
    }

    /**
     * This validates bank account type
     *
     * @throws InvalidArgumentException
     */
    private function validateAccountType(): void
    {
        $allowed = ['CHECKING', 'SAVINGS'];
        $accountType = $this->bank_account->accountType;
        if (! in_array($accountType, $allowed) || is_null($accountType)) {
            throw new InvalidArgumentException('accountType should be one of them: '.implode(', ', $allowed));
        }
    }
}
