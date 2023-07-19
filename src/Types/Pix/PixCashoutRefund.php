<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutRefundValidator;

class PixCashoutRefund implements PixCashoutInterface
{
    /** @var AddressingAccount */
    public AddressingAccount $account;

    /** @var string */
    public string $authenticationCode;

    /** @var string */
    public string $amount;

    /** @var string */
    public string $refundCode;

    /** @var string */
    public string $refundReason;

    /** @var string */
    public string $description;

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
