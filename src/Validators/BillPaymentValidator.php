<?php

namespace WeDevBr\Bankly\Validators;

use WeDevBr\Bankly\BillPayment;

/**
 * BillPaymentValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BillPaymentValidator
{
    private $billPayment;

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
        $this->validateBranch();
        $this->validateAccount();
        $this->validateAmount();
        $this->validateId();
    }

    /**
     * This validate a bank branch
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBranch()
    {
        $branch = $this->billPayment->branch;
        if (is_null($branch) || !is_string($branch) || !is_numeric($branch)) {
            throw new \InvalidArgumentException('branch should be a numeric string');
        }
    }

    /**
     * This validate a bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAccount()
    {
        $account = $this->billPayment->account;
        if (is_null($account) || !is_string($account)  || !is_numeric($account)) {
            throw new \InvalidArgumentException('account should be a numeric string');
        }
    }

    /**
     * This validates a amount
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAmount()
    {
        $amount = $this->billPayment->amount;
        if (is_null($amount) || !is_numeric($amount)) {
            throw new \InvalidArgumentException('amount should be a numeric');
        }
    }

    /**
     * This validates a given ID
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateId()
    {
        $id = $this->billPayment->id;
        if (is_null($id) || !is_string($id)) {
            throw new \InvalidArgumentException('ID should be a string');
        }
    }
}
