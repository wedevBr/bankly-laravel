<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\PixStaticQrCodeValidator;

class PixStaticQrCode
{
    /** @var \WeDevBr\Bankly\Types\Pix\AddressingKey */
    public $addressingKey;

    /** @var string */
    public $amount;

    /** @var string */
    public $recipientName;

    /** @var \WeDevBr\Bankly\Types\Pix\Location */
    public $location;

    /** @var string */
    public $categoryCode;

    /** @var string */
    public $additionalData;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate the PixStaticQrCode type
     *
     * @return void
     */
    public function validate(): void
    {
        $pixStaticQrCode = new PixStaticQrCodeValidator($this);
        $pixStaticQrCode->validate();
    }
}
