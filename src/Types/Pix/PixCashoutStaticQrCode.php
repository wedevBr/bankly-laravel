<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutStaticQrCodeValidator;

class PixCashoutStaticQrCode implements PixCashoutInterface
{
    /** @var string */
    public string $amount;

    /** @var string */
    public string $description;

    /** @var BankAccount */
    public BankAccount $sender;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     * @var string
     */
    public string $initializationType = 'StaticQrCode';

    /** @var string */
    public string $endToEndId;

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
     * This function validate the PixCashoutStaticQrCode type
     *
     * @return void
     */
    public function validate(): void
    {
        $pixCashout = new PixCashoutStaticQrCodeValidator($this);
        $pixCashout->validate();
    }
}
