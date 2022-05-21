<?php

namespace WeDevBr\Bankly\Types\Billet;

use WeDevBr\Bankly\Validators\Billet\FineValidator;

class Fine
{
    /** @var string */
    public $startDate;

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
     * This function validate a fine
     */
    public function validate()
    {
        $fineValidator = new FineValidator($this);
        $fineValidator->validate();
    }
}
