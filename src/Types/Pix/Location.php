<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\LocationValidator;

class Location implements Arrayable
{
    public string $city;

    public string $zipCode;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate the Location type
     */
    public function validate(): void
    {
        $locationValidator = new LocationValidator($this);
        $locationValidator->validate();
    }
}
