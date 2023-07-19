<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutKeyValidator;

class PixCashoutKey implements PixCashoutInterface
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
    public string $initializationType = 'Key';

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
