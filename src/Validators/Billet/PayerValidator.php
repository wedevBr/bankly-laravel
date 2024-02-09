<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\Address;
use WeDevBr\Bankly\Types\Billet\Payer;

/**
 * PayerValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PayerValidator
{
    private Payer $payer;

    public function __construct(Payer $payer)
    {
        $this->payer = $payer;
    }

    /**
     * Validate the attributes of the payer class
     */
    public function validate(): void
    {
        $this->validateDocument();
        $this->validateName();
        $this->validateTradeName();
        $this->validateAddress();
    }

    /**
     * This validates the document
     *
     * @throws InvalidArgumentException
     */
    private function validateDocument(): void
    {
        $document = $this->payer->document;
        if (empty($document) || ! is_string($document) || ! is_numeric($document)) {
            throw new InvalidArgumentException('payer document should be a numeric string');
        }
    }

    /**
     * This validates the payer name
     *
     * @throws InvalidArgumentException
     */
    private function validateName(): void
    {
        $name = $this->payer->name;
        if (empty($name) || ! is_string($name)) {
            throw new InvalidArgumentException('payer name should be a string');
        }
    }

    /**
     * This validates the payer trade name
     *
     * @throws InvalidArgumentException
     */
    private function validateTradeName(): void
    {
        $tradeName = $this->payer->tradeName;
        if (empty($tradeName) || ! is_string($tradeName)) {
            throw new InvalidArgumentException('payer trade name should be a string');
        }
    }

    /**
     * This validates a payer address
     *
     * @throws InvalidArgumentException
     */
    private function validateAddress(): void
    {
        if (! $this->payer->address instanceof Address) {
            throw new InvalidArgumentException('payer address should be a Address type');
        }

        $this->payer
            ->address
            ->validate();
    }
}
