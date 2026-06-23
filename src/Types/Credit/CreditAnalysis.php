<?php

namespace WeDevBr\Bankly\Types\Credit;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CreditAnalysisInterface;

class CreditAnalysis extends \stdClass implements Arrayable, CreditAnalysisInterface
{
    public ?string $name;

    public ?string $socialName;

    public ?string $motherName;

    public ?string $birthDate;

    public ?int $programId;

    public ?string $documentNumber;

    public ?string $profession;

    public ?string $maritalStatus;

    public ?string $academicDegree;

    public ?string $incomeBracket;

    public ?string $sex;

    public ?string $email;

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
