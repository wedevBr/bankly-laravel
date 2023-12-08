<?php

namespace WeDevBr\Bankly\Validators\Card;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Card\Card;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

/**
 * CardValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class CardValidator
{
    private Card $card;

    /**
     * CardValidator constructor.
     * @param Card $card
     */
    public function __construct(Card $card)
    {
        $this->card = $card;
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
    private function validateDocumentNumber(): void
    {
        $documentNumber = $this->card->documentNumber;
        if (empty($documentNumber) || !is_string($documentNumber) || !is_numeric($documentNumber)) {
            throw new InvalidArgumentException('document number should be a numeric string');
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
    private function validateCardName(): void
    {
        $cardName = $this->card->cardName;
        if (empty($cardName) || !is_string($cardName)) {
            throw new InvalidArgumentException('card name should be a string');
        }
    }

    /**
     * This validate a virtual card alias
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAlias(): void
    {
        $alias = $this->card->alias;
        if (empty($alias) || !is_string($alias)) {
            throw new InvalidArgumentException('alias should be a string');
        }
    }

    /**
     * This validate a virtual card bank agency
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankAgency(): void
    {
        $bankAgency = $this->card->bankAgency;
        if (empty($bankAgency) || !is_string($bankAgency) || !is_numeric($bankAgency) || strlen($bankAgency) != 4) {
            throw new InvalidArgumentException('bank agency should be a numeric string');
        }
    }

    /**
     * This validate a virtual card bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankAccount(): void
    {
        $bankAccount = $this->card->bankAccount;
        if (empty($bankAccount) || !is_string($bankAccount) || !is_numeric($bankAccount)) {
            throw new InvalidArgumentException('bank account should be a numeric string');
        }
    }

    /**
     * This validate a virtual card program ID
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateProgramId(): void
    {
        $programId = $this->card->programId;
        if (!empty($programId) && (!is_string($programId) || !is_numeric($programId))) {
            throw new InvalidArgumentException('program ID should be a numeric string');
        }
    }

    /**
     * This validate a virtual card password
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validatePassword(): void
    {
        $password = $this->card->password;
        if (empty($password) || !is_string($password) || !is_numeric($password) || strlen($password) != 4) {
            throw new InvalidArgumentException('password should be a numeric string');
        }
    }

    /**
     * This validate a virtual card address
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAddress(): void
    {
        $address = $this->card->address;
        $address->validate();
    }

    /**
     * This validate a card type
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateType(): void
    {
        $type = $this->card->type;
        if (empty($type) || !is_string($type)) {
            throw new InvalidArgumentException('type should be a string');
        }
    }
}
