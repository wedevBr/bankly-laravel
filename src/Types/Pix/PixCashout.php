<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\PixCashoutValidator;

class PixCashout implements Arrayable
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
    public $initializationType;

    /** @var string */
    public $addressKey;

    /**
     * Reconciliation identifier of the recipient.
     * Used by financial institutions to identify the relationship
     * between payments and QrCode.
     *
     * @var string
     */
    public $receiverReconciliationId;

    /** @var string */
    public $endToEndId;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return (array) $this;
    }

    /**
     * This function validate the PixCashout type
     *
     * @return void
     */
    public function validate()
    {
        $pixCashout = new PixCashoutValidator($this);
        $pixCashout->validate();
    }
}
