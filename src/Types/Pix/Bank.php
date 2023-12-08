<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\BankValidator;

class Bank implements Arrayable
{
    public ?string $ispb;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {

        $this->validate();
        $array = (array) $this;

        return array_filter($array);
    }

    /**
     * This function validate the PixCashout type
     *
     * @return void
     */
    public function validate()
    {
        $bank = new BankValidator($this);
        $bank->validate();
    }
}
