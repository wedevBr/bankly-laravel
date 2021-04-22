<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;

class AddressingKey
{
    /** @var string */
    public $type;

    /** @var string */
    public $value;

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
     * This function validate a Addressing Key
     */
    public function validate()
    {
        $validator = new AddressingKeyValidator($this);
        $validator->validate();
    }
}
