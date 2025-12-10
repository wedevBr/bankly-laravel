<?php

declare(strict_types=1);

namespace WeDevBr\Bankly\ValueObjects\AutomaticPix;

class Amount
{
    public function __construct(
        public string $fixedValue,
        public string $minimumValue,
        public string $maximumValue
    ) {}

    public function toArray(): array
    {
        return [
            'fixedValue' => $this->fixedValue,
            'minimumValue' => $this->minimumValue,
            'maximumValue' => $this->maximumValue,
        ];
    }
}
