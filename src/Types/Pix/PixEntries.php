<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Validators\Pix\AddressingAccountValidator;
use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;

class PixEntries
{
    /** @var AddressingKey */
    public $addressingKey;

    /** @var AddressingAccount */
    public $account;

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
        $addressingKeyValidator = new AddressingKeyValidator($this->addressingKey);
        $addressingKeyValidator->validate();

        $addressingAccountValidator = new AddressingAccountValidator($this->account);
        $addressingAccountValidator->validate();
    }
}
