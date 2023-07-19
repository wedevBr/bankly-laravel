<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\ChangeStatusValidator;

class ChangeStatus extends \stdClass implements Arrayable
{
    /** @var string */
    public string $password;

    /** @var string */
    public string $status;

    /** @var bool */
    public bool $updateCardBinded = false;

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
    public function validate(): self
    {
        $validator = new ChangeStatusValidator($this);
        $validator->validate();

        return $this;
    }
}
