<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\BankAccountValidator;

class BankAccount
{
    /** @var string */
    public $branch;

    /** @var string */
    public $number;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return (array) $this;
    }

    /**
     * This function validate a bank account for deposit billet
     */
    public function validate()
    {
        $bankAccountValidator = new BankAccountValidator($this);
        $bankAccountValidator->validate();
    }
}
