<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\Bank;

/**
 * BankValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BankValidator
{
    private Bank $bank;

    public function __construct(Bank $bank)
    {
        $this->bank = $bank;
    }

    /**
     * Validate the attributes of the bank class
     */
    public function validate(): void
    {
        $this->validateIspb();
    }

    /**
     * This validates a bank ispb
     *
     * @throws InvalidArgumentException
     */
    private function validateIspb(): void
    {
        $ispb = $this->bank->ispb;
        if (empty($ispb) || ! is_string($ispb) || ! is_numeric($ispb)) {
            throw new InvalidArgumentException('bank ispb should be a numeric string');
        }
    }
}
