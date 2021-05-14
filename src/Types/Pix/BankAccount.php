<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\BankAccountValidator;

class BankAccount implements Arrayable
{
    /** @var \WeDevBr\Bankly\Types\Pix\AddressingAccount */
    public $account;

    /** @var \WeDevBr\Bankly\Types\Pix\Bank */
    public $bank;

    /** @var string */
    public $documentNumber;

    /** @var string */
    public $name;

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
     * This function validate the PixCashout type
     *
     * @return void
     */
    public function validate()
    {
        $bankAccount = new BankAccountValidator($this);
        $bankAccount->validate();
    }
}
