<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\PixDynamicQrCodeValidator;

class PixDynamicQrCode implements Arrayable
{
    /** @var string */
    public string $recipientName;

    /** @var AddressingKey */
    public AddressingKey $addressingKey;

    /** @var ?string */
    public ?string $conciliationId;

    /** @var Payer */
    public Payer $payer;

    /** @var bool */
    public bool $singlePayment;

    /** @var string */
    public string $changeAmountType;

    /** @var ?string */
    public ?string $amount;

    /** @var ?string */
    public ?string $expiresAt;

    /** @var ?string */
    public ?string $payerRequestText;

    /** @var ?array */
    public ?array $additionalData;

    /**
     * This validates and return an array
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
        $pixStaticQrCode = new PixDynamicQrCodeValidator($this);
        $pixStaticQrCode->validate();
    }
}
