<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\WalletValidator;

class Wallet extends \stdClass implements Arrayable
{
    /** @var string */
    public string $proxy;

    /** @var string */
    public string $wallet;

    /** @var string */
    public string $brand;

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
     * This function validate a digital wallet
     */
    public function validate(): self
    {
        $validator = new WalletValidator($this);
        $validator->validate();

        return $this;
    }
}
