<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\PayerValidator;

class Payer
{
    /** @var Address */
    public $address;

    /** @var string */
    public $document;

    /** @var string */
    public $name;

    /** @var string */
    public $tradeName;

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
