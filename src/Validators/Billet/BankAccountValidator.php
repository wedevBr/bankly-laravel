<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\BankAccount;

/**
 * BankAccountValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BankAccountValidator
{
    /** @var BankAccount */
    private BankAccount $bankAccount;

    /**
     * @param BankAccount $bankAccount
     */
    public function __construct(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * Validate the attributes of the bank accoun class for deposit billet
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateBranch();
        $this->validateNumber();
    }

    /**
     * This validates the branch
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBranch(): void
    {
        $branch = $this->bankAccount->branch;
        if (empty($branch) || !is_string($branch) || !is_numeric($branch)) {
            throw new InvalidArgumentException('branch should be a numeric string');
        }
    }

    /**
     * This validates the bank account number
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateNumber(): void
    {
        $number = $this->bankAccount->number;
        if (empty($number) || !is_string($number) || !is_numeric($number)) {
            throw new InvalidArgumentException('bank account number should be a numeric string');
        }
    }
}
