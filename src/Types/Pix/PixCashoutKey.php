<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutKeyValidator;

class PixCashoutKey implements PixCashoutInterface
{
    public string $amount;

    public string $description;

    public mixed $sender;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     */
    public ?string $initializationType = 'Key';

    public ?string $endToEndId;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate the PixCashoutKeyValidator type
     */
    public function validate(): void
    {
        $pixCashout = new PixCashoutKeyValidator($this);
        $pixCashout->validate();
    }
}
