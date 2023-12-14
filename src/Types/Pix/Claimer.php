<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\BankValidator;
use WeDevBr\Bankly\Validators\Pix\PixClaimValidator;

class Claimer implements Arrayable
{
    public ?AddressingAccount $addressingAccount;

    public Bank $bank;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return [
            'branch' => $this->addressingAccount?->branch,
            'number' => $this->addressingAccount?->number,
            'bank' => $this->bank->toArray(),
        ];
    }

    /**
     * This function validate a Addressing Key
     */
    public function validate(): void
    {
        $bankValidator = new BankValidator($this->bank);
        $bankValidator->validate();

        if (! empty($this->addressingAccount)) {
            $pixClaimValidator = new PixClaimValidator($this->addressingAccount);
            $pixClaimValidator->validate();
        }
    }
}
