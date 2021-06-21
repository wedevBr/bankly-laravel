<?php

namespace WeDevBr\Bankly\Validators\Card;

use WeDevBr\Bankly\Types\Card\Address;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

/**
 * DuplicateCardValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
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
        'UnrecognizedOnlinePurchase'
    ];

    /**
     * DuplicateCardValidator constructor.
     *
     * @param Duplicate $duplicateCard
     */
    public function __construct(Duplicate $duplicateCard)
    {
        $this->duplicateCard = $duplicateCard;
    }

    /**
     * This validate the duplicate card data
     *
     * @return void
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
     * @return void
     * @throws \InvalidArgumentException
     */
    public function validateStatus()
    {
        $status = $this->duplicateCard->status;
        if (!in_array($status, $this->status)) {
            $message = 'invalid status, needs to be one of these';
            $message .= ' LostMyCard, CardWasStolen, CardWasDamaged, CardNotDelivered, UnrecognizedOnlinePurchase';
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * This validate a duplicate card document
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateDocumentNumber()
    {
        $documentNumber = $this->duplicateCard->documentNumber;
        if (empty($documentNumber) || !is_string($documentNumber) || !is_numeric($documentNumber)) {
            throw new \InvalidArgumentException('document number should be a numeric string');
        }

        $documentValidator = new CpfCnpjValidator($documentNumber);
        $documentValidator->validate();
    }

    /**
     * This validate a duplicate card password
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validatePassword()
    {
        $password = $this->duplicateCard->password;
        if (empty($password) || !is_string($password) || !is_numeric($password) || strlen($password) != 4) {
            throw new \InvalidArgumentException('password should be a numeric string');
        }
    }

    /**
     * This validate a duplicate card address
     *
     * @return void
     */
    private function validateAddress()
    {
        if ($this->duplicateCard->address instanceof Address) {
            $address = $this->duplicateCard->address;
            $address->validate();
        }
    }
}
