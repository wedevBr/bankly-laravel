<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\WalletValidator;

class Wallet extends \stdClass implements Arrayable
{
    public ?string $proxy;

    public ?string $wallet;

    public ?string $brand;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate a digital wallet
     */
    public function validate(): self
    {
        $validator = new WalletValidator($this);
        $validator->validate();

        return $this;
    }
}
