<?php

declare(strict_types=1);

namespace WeDevBr\Bankly\ValueObjects\AutomaticPix;

class Creditor
{
    public function __construct(
        public string $agentMemberIdentification,
        public string $name,
        public string $privateIdentification
    ) {}

    public function toArray(): array
    {
        return [
            'agentMemberIdentification' => $this->agentMemberIdentification,
            'name' => $this->name,
            'privateIdentification' => $this->privateIdentification,
        ];
    }
}
