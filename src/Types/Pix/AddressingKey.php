<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;

class AddressingKey
{
    /** @var string */
    public $type;

    /** @var string */
    public $value;

    /** @var bool */
    public $registering = true;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        $array = (array) $this;
        unset($array['registering']);
        return $array;
    }

    /**
     * This function validate a Addressing Key
     */
    public function validate()
    {
        $validator = new AddressingKeyValidator($this, $this->registering);
        $validator->validate();
    }
}
