<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutRefundValidator;

class PixCashoutRefund implements PixCashoutInterface
{
    /** @var \WeDevBr\Bankly\Types\Pix\AddressingAccount */
    public $account;

    /** @var string */
    public $authenticationCode;

    /** @var string */
    public $amount;

    /** @var string */
    public $refundCode;

    /** @var string */
    public $refundReason;

    /** @var string */
    public $description;

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
        $pixCashout = new PixCashoutRefundValidator($this);
        $pixCashout->validate();
    }
}
