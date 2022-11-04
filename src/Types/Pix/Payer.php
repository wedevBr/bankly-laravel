<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\PayerValidator;

class Payer
{
    /** @var string */
    public $name;

    /** @var string */
    public $documentNumber;

    /** @var string */
    public $type;

    /** @var Location */
    public $address;

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
    public function validate()
    {
        $payerValidator = new PayerValidator($this);
        $payerValidator->validate();
    }
}
