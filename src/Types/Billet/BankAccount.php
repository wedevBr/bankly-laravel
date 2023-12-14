<?php

namespace WeDevBr\Bankly\Types\Billet;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Billet\BankAccountValidator;

class BankAccount implements Arrayable
{
    public ?string $branch;

    public ?string $number;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate a bank account for deposit billet
     */
    public function validate(): void
    {
        $bankAccountValidator = new BankAccountValidator($this);
        $bankAccountValidator->validate();
    }
}
