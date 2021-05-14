<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutManualValidator;

class PixCashoutManual implements PixCashoutInterface
{
    /** @var string */
    public $amount;

    /** @var string */
    public $description;

    /** @var \WeDevBr\Bankly\Types\Pix\BankAccount */
    public $sender;

    /** @var \WeDevBr\Bankly\Types\Pix\BankAccount */
    public $recipient;

    /**
     * [Manual, Key, StaticQrCode, DynamicQrCode]
     * @var string
     */
    public $initializationType = 'Manual';

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
