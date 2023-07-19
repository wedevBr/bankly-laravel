<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\PayerValidator;

class Payer
{
    /** @var Address */
    public Address $address;

    /** @var string */
    public string $document;

    /** @var string */
    public string $name;

    /** @var string */
    public string $tradeName;

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
     * This function validate a payer
     */
    public function validate(): void
    {
        $payerValidator = new PayerValidator($this);
        $payerValidator->validate();
    }
}
