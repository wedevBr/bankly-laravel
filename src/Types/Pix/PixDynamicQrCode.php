<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\PixDynamicQrCodeValidator;

class PixDynamicQrCode implements Arrayable
{
    public string $recipientName;

    public AddressingKey $addressingKey;

    public ?string $conciliationId;

    public Payer $payer;

    public bool $singlePayment;

    public string $changeAmountType;

    public ?string $amount;

    public ?string $expiresAt;

    public ?string $payerRequestText;

    public ?array $additionalData;

    /**
     * This validates and return an array
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
        $pixStaticQrCode = new PixDynamicQrCodeValidator($this);
        $pixStaticQrCode->validate();
    }
}
