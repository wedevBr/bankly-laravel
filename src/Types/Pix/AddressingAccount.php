<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\AddressingAccountValidator;

class AddressingAccount
{
    /** @var string */
    public string $branch;

    /** @var string */
    public string $number;

    /** @var string */
    public string $type;

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
    public function validate(): void
    {
        $validator = new AddressingAccountValidator($this);
        $validator->validate();
    }
}
