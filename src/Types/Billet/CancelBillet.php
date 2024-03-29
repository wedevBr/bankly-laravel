<?php

namespace WeDevBr\Bankly\Types\Billet;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Billet\CancelBilletValidator;

class CancelBillet implements Arrayable
{
    public string $authenticationCode;

    public BankAccount $account;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate the billet cancellation
     */
    public function validate(): void
    {
        $cancelBilletValidator = new CancelBilletValidator($this);
        $cancelBilletValidator->validate();
    }
}
