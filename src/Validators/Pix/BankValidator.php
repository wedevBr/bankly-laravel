<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\Bank;

/**
 * BankValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BankValidator
{
    /** @var Bank */
    private $bank;

    /**
     * @param Bank $bank
     */
    public function __construct(Bank $bank)
    {
        $this->bank = $bank;
    }

    /**
     * Validate the attributes of the bank class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateIspb();
        $this->validateCompe();
        $this->validateName();
    }

    /**
     * This validates a bank ispb
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateIspb()
    {
        $ispb = $this->bank->ispb;
        if (empty($ispb) || !is_string($ispb) || !is_numeric($ispb)) {
            throw new \InvalidArgumentException('bank ispb should be a numeric string');
        }
    }

    /**
     * This validates a bank compe
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateCompe()
    {
        $compe = $this->bank->compe;
        if (empty($compe) || !is_string($compe) || !is_numeric($compe)) {
            throw new \InvalidArgumentException('bank compe account should be a numeric string');
        }
    }

    /**
     * This validates a bank name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateName()
    {
        $name = $this->bank->name;
        if (empty($name) || !is_string($name)) {
            throw new \InvalidArgumentException('bank name should be a string');
        }
    }
}
