<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutStaticQrCodeValidator;

class PixCashoutStaticQrCode implements PixCashoutInterface
{
    /** @var string */
    public $amount;

    /** @var string */
    public $description;

    /** @var \WeDevBr\Bankly\Types\Pix\BankAccount */
    public $sender;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     * @var string
     */
    public $initializationType = 'StaticQrCode';

    /** @var string */
    public $endToEndId;

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
