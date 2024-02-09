<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutManualValidator;

class PixCashoutManual extends PixCashoutKey implements PixCashoutInterface
{
    public mixed $recipient;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     */
    public ?string $initializationType = 'Manual';

    /**
     * This validates and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate the PixCashout type
     */
    public function validate(): void
    {
        $pixCashout = new PixCashoutManualValidator($this);
        $pixCashout->validate();
    }
}
