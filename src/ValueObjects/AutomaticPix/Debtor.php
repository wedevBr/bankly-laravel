<?php

declare(strict_types=1);

namespace WeDevBr\Bankly\ValueObjects\AutomaticPix;

class Debtor
{
    public function __construct(
        public string $accountIdentification,
        public string $accountIssuer,
        public string $agentMemberIdentification,
        public string $name,
        public string $privateIdentification,
        public ?string $cityCode = null,
    ) {}

    public function toArray(): array
    {
        return [
            'accountIdentification' => $this->accountIdentification,
            'accountIssuer' => $this->accountIssuer,
            'agentMemberIdentification' => $this->agentMemberIdentification,
            'cityCode' => $this->cityCode,
            'name' => $this->name,
            'privateIdentification' => $this->privateIdentification,
        ];
    }
}
