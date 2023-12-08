<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;

class AddressingKey implements Arrayable
{
    public ?string $type;

    public ?string $value;

    public bool $registering = true;

    /**
     * This validate and return an array
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
