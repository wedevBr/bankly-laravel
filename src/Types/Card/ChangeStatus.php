<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\ChangeStatusValidator;

class ChangeStatus extends \stdClass implements Arrayable
{
    public ?string $password;

    public ?string $status;

    public ?bool $updateCardBinded = false;

    /**
     * This validate and return an array
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
