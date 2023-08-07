<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\Location;
use WeDevBr\Bankly\Types\Pix\Payer;

/**
 * PayerValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @copyright 2022 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PayerValidator
{
    /** @var Payer */
    private Payer $payer;

    /**
     * @param Payer $payer
     */
    public function __construct(Payer $payer)
    {
        $this->payer = $payer;
    }

    /**
     * Validate the attributes of the payer class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateName();
        $this->validateDocumentNumber();
        $this->validateType();
        $this->validateAddress();
    }

    /**
     * This validates the payer name
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateName(): void
    {
        $name = $this->payer->name;
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('payer name should be a string');
        }
    }

    /**
     * This validates the document number
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateDocumentNumber(): void
    {
        $document = $this->payer->documentNumber;
        if (empty($document) || !is_string($document) || !is_numeric($document)) {
            throw new InvalidArgumentException('payer document number should be a numeric string');
        }
    }

    /**
     * This validates the payer type
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateType(): void
    {
        $type = $this->payer->type;
        $types = [
            'CUSTOMER',
            'BUSINESS'
        ];
        if (empty($type) || !in_array($type, $types)) {
            throw new InvalidArgumentException('payer type is not valid');
        }
    }

    /**
     * This validates a payer address
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAddress(): void
    {
        if (!$this->payer->address instanceof Location) {
            throw new InvalidArgumentException('payer address should be a Localtion type');
        }

        $this->payer
            ->address
            ->validate();
    }
}
