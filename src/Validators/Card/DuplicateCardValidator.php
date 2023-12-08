<?php

namespace WeDevBr\Bankly\Validators\Card;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Card\Address;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

/**
 * DuplicateCardValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class DuplicateCardValidator
{
    /**
     * @var Duplicate
     */
    private $duplicateCard;

    /**
     * @var array
     */
    private $status = [
        'LostMyCard',
        'CardWasStolen',
        'CardWasDamaged',
        'CardNotDelivered',
        'UnrecognizedOnlinePurchase',
    ];

    /**
     * DuplicateCardValidator constructor.
     */
    public function __construct(Duplicate $duplicateCard)
    {
        $this->duplicateCard = $duplicateCard;
    }

    /**
     * This validate the duplicate card data
     */
    public function validate(): void
    {
        $this->validateStatus();
        $this->validateDocumentNumber();
        $this->validatePassword();
        $this->validateAddress();
    }

    /**
     * This validate duplicate card status
     *
     * @throws InvalidArgumentException
     */
    public function validateStatus(): void
    {
        $status = $this->duplicateCard->status;
        if (! in_array($status, $this->status)) {
            $message = 'invalid status, needs to be one of these';
            $message .= ' LostMyCard, CardWasStolen, CardWasDamaged, CardNotDelivered, UnrecognizedOnlinePurchase';
            throw new InvalidArgumentException($message);
        }
    }

    /**
     * This validate a duplicate card document
     *
     * @throws InvalidArgumentException
     */
    private function validateDocumentNumber(): void
    {
        $documentNumber = $this->duplicateCard->documentNumber;
        if (empty($documentNumber) || ! is_string($documentNumber) || ! is_numeric($documentNumber)) {
            throw new InvalidArgumentException('document number should be a numeric string');
        }

        $documentValidator = new CpfCnpjValidator($documentNumber);
        $documentValidator->validate();
    }

    /**
     * This validate a duplicate card password
     *
     * @throws InvalidArgumentException
     */
    private function validatePassword(): void
    {
        $password = $this->duplicateCard->password;
        if (empty($password) || ! is_string($password) || ! is_numeric($password) || strlen($password) != 4) {
            throw new InvalidArgumentException('password should be a numeric string');
        }
    }

    /**
     * This validate a duplicate card address
     */
    private function validateAddress(): void
    {
        if ($this->duplicateCard->address instanceof Address) {
            $address = $this->duplicateCard->address;
            $address->validate();
        }
    }
}
