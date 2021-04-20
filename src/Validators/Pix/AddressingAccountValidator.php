<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\AddressingAccount;

/**
 * AddressingAccountValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class AddressingAccountValidator
{
    /** @var AddressingAccount */
    private $addressingAccount;

    /**
     * @param AddressingAccount $addressingAccount
     */
    public function __construct(AddressingAccount $addressingAccount)
    {
        $this->addressingAccount = $addressingAccount;
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
        $this->validateType();
    }

    /**
     * This validates a branch account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBranch()
    {
        $branch = $this->addressingAccount->branch;
        if (empty($branch) || !is_string($branch) || !is_numeric($branch)) {
            throw new \InvalidArgumentException('branch should be a numeric string');
        }
    }

    /**
     * This validates a number account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateNumber()
    {
        $number = $this->addressingAccount->number;
        if (empty($number) || !is_string($number) || !is_numeric($number)) {
            throw new \InvalidArgumentException('number account should be a numeric string');
        }
    }

    /**
     * This validates a type account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateType()
    {
        $type = $this->addressingAccount->type;
        if (empty($type) || !is_string($type)) {
            throw new \InvalidArgumentException('type account should be a string');
        }

        $typeList = [
            'CHECKING',
            'SAVINGS',
            'SALARY',
        ];
        if (!in_array($this->addressingAccount->type, $typeList)) {
            throw new \InvalidArgumentException('this account type is not valid');
        }
    }
}
