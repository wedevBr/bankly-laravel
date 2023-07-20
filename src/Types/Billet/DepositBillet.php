<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\DepositBilletValidator;

class DepositBillet
{
    /** @var mixed */
    public mixed $account;

    /** @var mixed */
    public mixed $payer;

    /** @var string|null */
    public ?string $alias;

    /** @var string|null */
    public ?string $documentNumber;

    /** @var string|null */
    public ?string $amount;

    /** @var string */
    public string $dueDate;

    /**
     * [Deposit, Levy]
     * @var string
     * */
    public string $type;

    /** @var string|null */
    public ?string $closePayment = null;

    /** @var Interest */
    public Interest $interest;

    /** @var Fine */
    public Fine $fine;

    /** @var Discounts */
    public Discounts $discounts;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate a virtual card address
     */
    public function validate(): void
    {
        $depositBilletValidator = new DepositBilletValidator($this);
        $depositBilletValidator->validate();
    }
}
