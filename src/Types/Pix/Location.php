<?php

namespace WeDevBr\Bankly\Types\Pix;

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
        return (array) $this;
    }
}
