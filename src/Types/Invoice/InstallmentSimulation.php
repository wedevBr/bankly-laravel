<?php

namespace WeDevBr\Bankly\Types\Invoice;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\InstallmentSimulationInterface;

class InstallmentSimulation extends \stdClass implements Arrayable, InstallmentSimulationInterface
{
    public int $minTerm;

    public int $maxTerm;

    public ?float $downPayment;

    public function toArray(): array
    {
        return (array) $this;
    }
}
