<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Validators\Pix\PixCashoutRefundValidator;

class PixCashoutRefund implements PixCashoutInterface
{
    public mixed $account;

    public ?string $authenticationCode;

    public string $amount;

    public ?string $refundCode;

    /** @var string|null */
    public mixed $refundReason = null;

    /** @var string */
    public mixed $description;

    /**
     * This validate and return an array
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
        $pixCashout = new PixCashoutRefundValidator($this);
        $pixCashout->validate();
    }
}
