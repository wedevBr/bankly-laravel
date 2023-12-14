<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Enums\Pix\ClaimTypeEnum;
use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;

class PixClaim implements Arrayable
{
    public Claimer $claimer;

    public ClaimTypeEnum $type;

    public AddressingKey $addressingKey;

    public function __construct(AddressingKey $addressingKey, Claimer $claimer, ClaimTypeEnum $type)
    {

        $this->addressingKey = $addressingKey;
        $this->claimer = $claimer;
        $this->type = $type;
    }

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {

        $this->validate();

        return [
            'type' => $this->type->value,
            'claimer' => $this->claimer->toArray(),
            'addressingKey' => $this->addressingKey->toArray(),

        ];
    }

    /**
     * This function validate a Addressing Key
     */
    public function validate(): void
    {
        $addressingKeyValidator = new AddressingKeyValidator($this->addressingKey);
        $addressingKeyValidator->validate();
    }
}
