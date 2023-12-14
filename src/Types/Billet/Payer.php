<?php

namespace WeDevBr\Bankly\Types\Billet;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Billet\PayerValidator;

class Payer implements Arrayable
{
    public mixed $address;

    public ?string $document;

    public ?string $name;

    public ?string $tradeName;

    /**
     * This validate and return an array
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
