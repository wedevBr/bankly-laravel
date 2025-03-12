<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;

class Documentation implements Arrayable
{
    public string $articlesOfIncorporation;

    public string $lastContractChange;

    public ?string $balanceSheet;

    public string $powerOfAttorney;

    public LegalRepresentative $legalRepresentative;

    public function setLegalRepresentative(LegalRepresentative $legalRepresentative): Documentation
    {
        $this->legalRepresentative = $legalRepresentative;

        return $this;
    }

    /**
     * This return an array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
