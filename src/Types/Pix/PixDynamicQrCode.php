<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\PixDynamicQrCodeValidator;

class PixDynamicQrCode
{
    /** @var string */
    public $recipientName;

    /** @var \WeDevBr\Bankly\Types\Pix\AddressingKey */
    public $addressingKey;

    /** @var string */
    public $conciliationId;

    /** @var \WeDevBr\Bankly\Types\Pix\Payer */
    public $payer;

    /** @var bool */
    public $singlePayment;

    /** @var string */
    public $changeAmountType;

    /** @var string */
    public $amount;

    /** @var string */
    public $expiresAt;

    /** @var string */
    public $payerRequestText;

    /** @var array */
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
        $pixStaticQrCode = new PixDynamicQrCodeValidator($this);
        $pixStaticQrCode->validate();
    }
}