<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\PayerValidator;

class Payer implements Arrayable
{
    /** @var ?string */
    public ?string $name;

    /** @var ?string */
    public ?string $documentNumber;

    /** @var string */
    public string $type;

    /** @var Location */
    public Location $address;

    /**
     * This validates and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return (array) $this;
    }

    /**
     * This function validate a payer
     */
    public function validate(): void
    {
        $payerValidator = new PayerValidator($this);
        $payerValidator->validate();
    }
}
