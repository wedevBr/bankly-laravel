<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\DepositBilletValidator;

class DepositBillet
{
    /** @var BankAccount */
    public $account;

    /** @var Payer */
    public $payer;

    /** @var string */
    public $alias;

    /** @var string */
    public $documentNumber;

    /** @var string */
    public $amount;

    /** @var string */
    public $dueDate;

    /**
     * [Deposit, Levy]
     * @var string
     * */
    public $type;

    /** @var string */
    public $closePayment;

    /** @var Interest */
    public $interest;

    /** @var Fine */
    public $fine;

    /** @var Discounts */
    public $discounts;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return json_decode(json_encode($this), true);;
    }

    /**
     * This function validate a virtual card address
     */
    public function validate()
    {
        $depositBilletValidator = new DepositBilletValidator($this);
        $depositBilletValidator->validate();
    }
}
