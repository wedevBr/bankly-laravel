<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\BankAccountValidator;

class BankAccount implements Arrayable
{
    /** @var AddressingAccount */
    public $account;

    /** @var Bank */
    public $bank;

    /** @var string|null */
    public ?string $documentNumber;

    /** @var string|null */
    public ?string $name;

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
    public function validate(): void
    {
        $bankAccount = new BankAccountValidator($this);
        $bankAccount->validate();
    }
}
