<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\ActivateValidator;

class Activate extends \stdClass implements Arrayable
{
    public string $activateCode;

    public string $password;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate the activate card data
     */
    public function validate(): void
    {
        $validator = new ActivateValidator($this);
        $validator->validate();
    }
}
