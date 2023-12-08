<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\PixStaticQrCodeValidator;

class PixStaticQrCode implements Arrayable
{
    public AddressingKey $addressingKey;

    /** @var ?string */
    public ?string $amount;

    public ?string $recipientName;

    public Location $location;

    /** @var ?string */
    public ?string $categoryCode;

    public mixed $additionalData = null;

    public ?string $conciliationId = null;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate the PixStaticQrCode type
     */
    public function validate(): void
    {
        $pixStaticQrCode = new PixStaticQrCodeValidator($this);
        $pixStaticQrCode->validate();
    }
}
