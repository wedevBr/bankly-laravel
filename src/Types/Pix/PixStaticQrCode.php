<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\PixStaticQrCodeValidator;

class PixStaticQrCode
{
    /** @var AddressingKey */
    public AddressingKey $addressingKey;

    /** @var ?string */
    public ?string $amount;

    /** @var string|null */
    public ?string $recipientName;

    /** @var Location */
    public Location $location;

    /** @var ?string */
    public ?string $categoryCode;

    /** @var mixed */
    public mixed $additionalData = null;

    /** @var string|null */
    public ?string $conciliationId = null;

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
