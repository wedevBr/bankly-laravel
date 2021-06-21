<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\BankAccount;

/**
 * BankAccountValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BankAccountValidator
{
    /** @var BankAccount */
    private $bankAccount;

    /**
     * @param BankAccount $bankAccount
     */
    public function __construct(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * Validate the attributes of the bank account class
     *
     * @return void
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
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAccount()
    {
        $this->bankAccount->account->validate();
    }

    /**
     * This validates a bank
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateBank()
    {
        $this->bankAccount->bank->validate();
    }

    /**
     * This validates a document number
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateDocumentNumber()
    {
        $documentNumber = $this->bankAccount->documentNumber;
        if (empty($documentNumber) || !is_string($documentNumber) || !is_numeric($documentNumber)) {
            throw new \InvalidArgumentException('document number should be a numeric string');
        }
    }

    /**
     * This validates a name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateName()
    {
        $name = $this->bankAccount->name;
        if (empty($name) || !is_string($name)) {
            throw new \InvalidArgumentException('name should be a string');
        }
    }
}
