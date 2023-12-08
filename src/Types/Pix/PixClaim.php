<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Pix\AddressingAccountValidator;
use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;
use WeDevBr\Bankly\Enums\Pix\ClaimTypeEnum;

class PixClaim implements Arrayable
{
    public Claimer $claimer;

    public ClaimTypeEnum $type;

    /** @var AddressingKey */
    public AddressingKey $addressingKey;


    public function __construct(AddressingKey $addressingKey, Claimer $claimer, ClaimTypeEnum $type) {

        $this->addressingKey = $addressingKey;
        $this->claimer = $claimer;
        $this->type = $type;
    }

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {

        $this->validate();
        return [
            'type' => $this->type->value,
            'claimer' => $this->claimer->toArray(),
            'addressingKey' => $this->addressingKey->toArray()

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
