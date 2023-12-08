<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\PixQrCodeDataValidator;

class PixQrCodeData implements Arrayable
{
    public ?string $encodedValue;

    public ?string $documentNumber;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate the PixQrCodeData type
     */
    public function validate(): void
    {
        $pixCashout = new PixQrCodeDataValidator($this);
        $pixCashout->validate();
    }
}
