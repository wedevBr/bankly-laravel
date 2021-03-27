<?php

namespace WeDevBr\Bankly\Types\VirtualCard;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\VirtualCard\VirtualCardValidator;

class VirtualCard extends \stdClass implements Arrayable
{
    /** @var string */
    public $documentNumber;

    /** @var string */
    public $cardName;

    /** @var string */
    public $alias;

    /** @var string */
    public $bankAgency;

    /** @var string */
    public $bankAccount;

    /** @var string */
    public $programId;

    /** @var string */
    public $password;

    /** @var \WeDevBr\Bankly\Types\VirtualCard\Address */
    public $address;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        $this->address = $this->address->toArray();

        return (array) $this;
    }

    /**
     * This function validate a virtual card address
     */
    public function validate()
    {
        $validator = new VirtualCardValidator($this);
        $validator->validate();

        return $this;
    }
}
