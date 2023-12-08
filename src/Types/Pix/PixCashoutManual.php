<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutManualValidator;

class PixCashoutManual extends PixCashoutKey implements PixCashoutInterface
{
    /** @var mixed */
    public mixed $recipient;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     * @var string|null
     */
    public ?string $initializationType = 'Manual';

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
     * This function validate the PixCashout type
     *
     * @return void
     */
    public function validate(): void
    {
        $pixCashout = new PixCashoutManualValidator($this);
        $pixCashout->validate();
    }
}
