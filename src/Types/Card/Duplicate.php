<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Card\DuplicateCardValidator;

class Duplicate extends \stdClass implements Arrayable
{
    /** @var string */
    public string $status;

    /** @var string|null */
    public ?string $documentNumber;

    /** @var string */
    public string $description;

    /** @var string */
    public string $password;

    /** @var \WeDevBr\Bankly\Types\Card\Address */
    public $address;

    /**
     * This validate and return an array
     *
     * @return array
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
