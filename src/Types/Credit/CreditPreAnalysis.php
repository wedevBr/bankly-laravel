<?php

namespace WeDevBr\Bankly\Types\Credit;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CreditPreAnalysisInterface;

class CreditPreAnalysis extends \stdClass implements Arrayable, CreditPreAnalysisInterface
{
    public ?string $documentNumber;

    public ?int $programId;

    public ?string $name;

    public ?string $motherName;

    public ?string $birthDate;

    public ?string $email;

    public ?string $profession;

    public ?string $maritalStatus;

    public function toArray(): array
    {
        return (array) $this;
    }
}
