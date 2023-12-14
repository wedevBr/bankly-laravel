<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\PasswordValidator;

class Password extends \stdClass implements Arrayable
{
    public ?string $password;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate a card password
     */
    public function validate(): self
    {
        $validator = new PasswordValidator($this);
        $validator->validate();

        return $this;
    }
}
