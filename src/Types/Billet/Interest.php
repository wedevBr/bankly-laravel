<?php

namespace WeDevBr\Bankly\Types\Billet;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Billet\InterestValidator;

class Interest implements Arrayable
{
    public string $startDate;

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
     * This function validate a interest
     */
    public function validate(): void
    {
        $interestValidator = new InterestValidator($this);
        $interestValidator->validate();
    }
}
