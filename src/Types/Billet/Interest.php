<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\InterestValidator;

class Interest
{
    /** @var string */
    public string $startDate;

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
     * This function validate a interest
     */
    public function validate(): void
    {
        $interestValidator = new InterestValidator($this);
        $interestValidator->validate();
    }
}
