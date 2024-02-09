<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\DuplicateCardValidator;

class Duplicate extends \stdClass implements Arrayable
{
    public string $status;

    public ?string $documentNumber;

    public string $description;

    public string $password;

    /** @var \WeDevBr\Bankly\Types\Card\Address */
    public $address;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();
        if ($this->address instanceof Address) {
            $this->address = $this->address;
        }

        return json_decode(json_encode($this), true);
    }

    /**
     * This function validate the data for duplicate card
     *
     * @return self
     */
    public function validate(): static
    {
        $validator = new DuplicateCardValidator($this);
        $validator->validate();

        return $this;
    }
}
