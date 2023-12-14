<?php

namespace WeDevBr\Bankly\Types\Billet;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Billet\DiscountsValidator;

class Discounts implements Arrayable
{
    public string $limitDate;

    public string $value;

    public string $type;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate a discounts
     */
    public function validate(): void
    {
        $discountsValidator = new DiscountsValidator($this);
        $discountsValidator->validate();
    }
}
