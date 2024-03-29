<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;

/**
 * AddressingAccountValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class AddressingAccountValidator
{
    private AddressingAccount $addressingAccount;

    public function __construct(AddressingAccount $addressingAccount)
    {
        $this->addressingAccount = $addressingAccount;
    }

    /**
     * Validate the attributes of the Addressing Account class
     */
    public function validate(): void
    {
        $this->validateBranch();
        $this->validateNumber();
        $this->validateType();
    }

    /**
     * This validates a branch account
     *
     * @throws InvalidArgumentException
     */
    protected function validateBranch(): void
    {
        $branch = $this->addressingAccount->branch;
        if (empty($branch) || ! is_string($branch) || ! is_numeric($branch)) {
            throw new InvalidArgumentException('branch should be a numeric string');
        }
    }

    /**
     * This validates a number account
     *
     * @throws InvalidArgumentException
     */
    protected function validateNumber(): void
    {
        $number = $this->addressingAccount->number;
        if (empty($number) || ! is_string($number) || ! is_numeric($number)) {
            throw new InvalidArgumentException('number account should be a numeric string');
        }
    }

    /**
     * This validates a type account
     *
     * @throws InvalidArgumentException
     */
    private function validateType(): void
    {
        $type = $this->addressingAccount->type;
        if (empty($type) || ! is_string($type)) {
            throw new InvalidArgumentException('type account should be a string');
        }

        $typeList = [
            'CHECKING',
            'SAVINGS',
            'SALARY',
            'PAYMENT',
        ];
        if (! in_array($this->addressingAccount->type, $typeList)) {
            throw new InvalidArgumentException('this account type is not valid');
        }
    }
}
