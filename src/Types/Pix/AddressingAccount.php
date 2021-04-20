<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\AddressingAccountValidator;

class AddressingAccount
{
    /** @var string */
    public $branch;

    /** @var string */
    public $number;

    /** @var string */
    public $type;

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
     * This function validate a Addressing Account
     */
    public function validate()
    {
        $validator = new AddressingAccountValidator($this);
        $validator->validate();
    }
}
