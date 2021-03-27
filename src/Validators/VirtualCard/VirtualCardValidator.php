<?php

namespace WeDevBr\Bankly\Validators\VirtualCard;

use WeDevBr\Bankly\Types\VirtualCard\VirtualCard;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

/**
 * VirtualCardValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class VirtualCardValidator
{
    private $virtualCard;

    /**
     * VirtualCardValidator constructor.
     * @param VirtualCard $virtualCard
     */
    public function __construct(VirtualCard $virtualCard)
    {
        $this->virtualCard = $virtualCard;
    }

    /**
     * This validate the virtual card
     */
    public function validate(): void
    {
        $this->validateDocumentNumber();
        $this->validateCardName();
        $this->validateAlias();
        $this->validateBankAgency();
        $this->validateBankAccount();
        $this->validateProgramId();
        $this->validatePassword();
        $this->validateAddress();
    }

    /**
     * This validate a virtual card document
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateDocumentNumber()
    {
        $documentNumber = $this->virtualCard->documentNumber;
        if (empty($documentNumber) || !is_string($documentNumber) || !is_numeric($documentNumber)) {
            throw new \InvalidArgumentException('document number should be a numeric string');
        }

        $documentValidator = new CpfCnpjValidator($documentNumber);
        $documentValidator->validate();
    }

    /**
     * This validates a card name
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateCardName()
    {
        $cardName = $this->virtualCard->cardName;
        if (empty($cardName) || !is_string($cardName)) {
            throw new \InvalidArgumentException('card name should be a string');
        }
    }

    /**
     * This validate a virtual card alias
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAlias()
    {
        $alias = $this->virtualCard->alias;
        if (empty($alias) || !is_string($alias)) {
            throw new \InvalidArgumentException('alias should be a string');
        }
    }

    /**
     * This validate a virtual card bank agency
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankAgency()
    {
        $bankAgency = $this->virtualCard->bankAgency;
        if (empty($bankAgency) || !is_string($bankAgency) || !is_numeric($bankAgency) || strlen($bankAgency) != 4) {
            throw new \InvalidArgumentException('bank agency should be a numeric string');
        }
    }

    /**
     * This validate a virtual card bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankAccount()
    {
        $bankAccount = $this->virtualCard->bankAccount;
        if (empty($bankAccount) || !is_string($bankAccount) || !is_numeric($bankAccount)) {
            throw new \InvalidArgumentException('bank account should be a numeric string');
        }
    }

    /**
     * This validate a virtual card program ID
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateProgramId()
    {
        $programId = $this->virtualCard->programId;
        if (!empty($programId) && (!is_string($programId) || !is_numeric($programId) || strlen($programId) != 32)) {
            throw new \InvalidArgumentException('program ID should be a numeric string');
        }
    }

    /**
     * This validate a virtual card password
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validatePassword()
    {
        $password = $this->virtualCard->password;
        if (empty($password) || !is_string($password) || !is_numeric($password) || strlen($password) != 4) {
            throw new \InvalidArgumentException('password should be a numeric string');
        }
    }

    /**
     * This validate a virtual card address
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAddress()
    {
        $address = $this->virtualCard->address;
        $address->validate();
    }
}
