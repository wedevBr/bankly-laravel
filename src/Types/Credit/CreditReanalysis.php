<?php

namespace WeDevBr\Bankly\Types\Credit;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CreditReanalysisInterface;

class CreditReanalysis extends \stdClass implements Arrayable, CreditReanalysisInterface
{
    public ?string $documentNumber;

    public ?int $programId;

    public ?string $name;

    public ?string $motherName;

    public ?string $birthDate;

    public ?string $email;

    public ?string $profession;

    public ?string $maritalStatus;

    public ?string $academicDegree;

    public ?string $incomeBracket;

    public ?string $sex;

    public mixed $phone = null;

    public mixed $address = null;

    public mixed $scr = null;

    public function toArray(): array
    {
        $this->phone = is_object($this->phone) ? $this->phone->toArray() : $this->phone;
        $this->address = is_object($this->address) ? $this->address->toArray() : $this->address;
        $this->scr = (array) $this->scr;

        return (array) $this;
    }
}
