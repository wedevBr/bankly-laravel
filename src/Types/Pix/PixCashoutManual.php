<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutManualValidator;

class PixCashoutManual implements PixCashoutInterface
{
    /** @var string */
    public string $amount;

    /** @var string */
    public string $description;

    /** @var mixed */
    public mixed $sender;

    /** @var mixed */
    public mixed $recipient;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     * @var string|null
     */
    public ?string $initializationType = 'Manual';

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
