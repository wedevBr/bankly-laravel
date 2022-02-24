<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutKeyValidator;

class PixCashoutKey implements PixCashoutInterface
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
    public $initializationType = 'Key';

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
     * This function validate the PixCashoutKeyValidator type
     *
     * @return void
     */
    public function validate(): void
    {
        $pixCashout = new PixCashoutKeyValidator($this);
        $pixCashout->validate();
    }
}
