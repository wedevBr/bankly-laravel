<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;

class Document extends \stdClass implements Arrayable
{
    public string $value;

    /**
     * This return an array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
