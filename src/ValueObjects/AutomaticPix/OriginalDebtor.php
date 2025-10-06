<?php

declare(strict_types=1);

namespace WeDevBr\Bankly\ValueObjects\AutomaticPix;

class OriginalDebtor
{
    public function __construct(
        public string $name,
        public string $privateIdentification
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'privateIdentification' => $this->privateIdentification,
        ];
    }
}
