<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;

/**
 * AddressingAccountValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixClaimValidator extends AddressingAccountValidator
{
    /** @var AddressingAccount */
    private AddressingAccount $addressingAccount;

    /**
     * @param AddressingAccount $addressingAccount
     */
    public function __construct(AddressingAccount $addressingAccount)
    {
        parent::__construct($addressingAccount);
    }

    /**
     * Validate the attributes of the Addressing Account class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateBranch();
        $this->validateNumber();
    }
}
