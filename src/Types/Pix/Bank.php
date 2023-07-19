<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\BankValidator;

class Bank implements Arrayable
{
    /** @var string */
    public string $ispb;

    /** @var string */
    public string $compe;

    /** @var string */
    public string $name;

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
        $bank = new BankValidator($this);
        $bank->validate();
    }
}
