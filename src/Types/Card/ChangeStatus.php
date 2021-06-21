<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\ChangeStatusValidator;

class ChangeStatus extends \stdClass implements Arrayable
{
    /** @var string */
    public $password;

    /** @var string */
    public $status;

    /** @var bool */
    public $updateCardBinded = false;

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
     * This function validate a change status
     */
    public function validate()
    {
        $validator = new ChangeStatusValidator($this);
        $validator->validate();

        return $this;
    }
}
