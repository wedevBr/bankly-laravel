<?php

namespace WeDevBr\Bankly\Validators\Billet;

use WeDevBr\Bankly\Types\Billet\Address;
use WeDevBr\Bankly\Types\Billet\Payer;

/**
 * PayerValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PayerValidator
{
    /** @var Payer */
    private $payer;

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
        $this->validateDocument();
        $this->validateName();
        $this->validateTradeName();
        $this->validateAddress();
    }

    /**
     * This validates the document
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateDocument()
    {
        $document = $this->payer->document;
        if (empty($document) || !is_string($document) || !is_numeric($document)) {
            throw new \InvalidArgumentException('payer document should be a numeric string');
        }
    }

    /**
     * This validates the payer name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateName()
    {
        $name = $this->payer->name;
        if (empty($name) || !is_string($name)) {
            throw new \InvalidArgumentException('payer name should be a string');
        }
    }

    /**
     * This validates the payer trade name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateTradeName()
    {
        $tradeName = $this->payer->tradeName;
        if (empty($tradeName) || !is_string($tradeName)) {
            throw new \InvalidArgumentException('payer trade name should be a string');
        }
    }

    /**
     * This validates a payer address
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAddress()
    {
        if (!$this->payer->address instanceof Address) {
            throw new \InvalidArgumentException('payer address should be a Address type');
        }

        $this->payer
            ->address
            ->validate();
    }
}
