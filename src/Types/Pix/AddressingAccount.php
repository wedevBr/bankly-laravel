<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\AddressingAccountValidator;

class AddressingAccount implements Arrayable
{
    /** @var string|null */
    public ?string $branch;

    /** @var string|null */
    public ?string $number;

    /** @var string|null */
    public ?string $type;

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
     * This function validate a Addressing Account
     */
    public function validate(): void
    {
        $validator = new AddressingAccountValidator($this);
        $validator->validate();
    }
}
