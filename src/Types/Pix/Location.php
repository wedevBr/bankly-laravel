<?php

namespace WeDevBr\Bankly\Types\Pix;

use WeDevBr\Bankly\Validators\Pix\LocationValidator;

class Location
{
    /** @var string */
    public $city;

    /** @var string */
    public $zipCode;

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
     * This function validate the Location type
     *
     * @return void
     */
    public function validate(): void
    {
        $locationValidator = new LocationValidator($this);
        $locationValidator->validate();
    }
}
