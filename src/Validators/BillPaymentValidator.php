<?php

namespace WeDevBr\Bankly\Validators;

use InvalidArgumentException;
use WeDevBr\Bankly\BillPayment;

/**
 * BillPaymentValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BillPaymentValidator
{
    private BillPayment $billPayment;

    /**
     * BillPaymentValidator constructor.
     * @param BillPayment $billPayment
     */
    public function __construct(BillPayment $billPayment)
    {
        $this->billPayment = $billPayment;
    }

    /**
     * This validate the bank account
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateBankBranch();
        $this->validateBankAccount();
        $this->validateAmount();
        $this->validateId();
    }

    /**
     * This validate a bank branch
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankBranch(): void
    {
        $bankBranch = $this->billPayment->bankBranch;
        if (is_null($bankBranch) || !is_string($bankBranch) || !is_numeric($bankBranch)) {
            throw new InvalidArgumentException('branch should be a numeric string');
        }
    }

    /**
     * This validate a bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankAccount(): void
    {
        $bankAccount = $this->billPayment->bankAccount;
        if (is_null($bankAccount) || !is_string($bankAccount)  || !is_numeric($bankAccount)) {
            throw new InvalidArgumentException('account should be a numeric string');
        }
    }

    /**
     * This validates a amount
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAmount(): void
    {
        $amount = $this->billPayment->amount;
        if (is_null($amount) || !is_numeric($amount)) {
            throw new InvalidArgumentException('amount should be a numeric');
        }
    }

    /**
     * This validates a given ID
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateId(): void
    {
        $id = $this->billPayment->id;
        if (is_null($id) || !is_string($id)) {
            throw new InvalidArgumentException('ID should be a string');
        }
    }
}
