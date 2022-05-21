<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\DiscountsValidator;

class Discounts
{
    /** @var string */
    public $limitDate;

    /** @var string */
    public $value;

    /** @var string */
    public $type;

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
    public function validate()
    {
        $discountsValidator = new DiscountsValidator($this);
        $discountsValidator->validate();
    }
}
