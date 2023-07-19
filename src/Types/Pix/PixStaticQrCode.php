<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\PixStaticQrCodeValidator;

class PixStaticQrCode
{
    /** @var AddressingKey */
    public AddressingKey $addressingKey;

    /** @var string */
    public string $amount;

    /** @var string */
    public string $recipientName;

    /** @var Location */
    public Location $location;

    /** @var string */
    public string $categoryCode;

    /** @var string */
    public string $additionalData;

    /** @var string */
    public string $conciliationId;

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
