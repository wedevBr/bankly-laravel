<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\DiscountsValidator;

class Discounts
{
    /** @var string */
    public string $limitDate;

    /** @var string */
    public string $value;

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
     * This function validate a discounts
     */
    public function validate(): void
    {
        $discountsValidator = new DiscountsValidator($this);
        $discountsValidator->validate();
    }
}
