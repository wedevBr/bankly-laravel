<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\PixQrCodeDataValidator;

class PixQrCodeData
{
    /** @var string */
    public $encodedValue;

    /** @var string */
    public $clientDocumentNumber;

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
     * This function validate the PixQrCodeData type
     *
     * @return void
     */
    public function validate(): void
    {
        $pixCashout = new PixQrCodeDataValidator($this);
        $pixCashout->validate();
    }
}
