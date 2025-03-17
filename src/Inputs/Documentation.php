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

    public function setArticlesOfIncoporation(string $articlesOfIncorporation): Documentation
    {
        $this->articlesOfIncorporation = $articlesOfIncorporation;

        return $this;
    }

    public function setLastContractChange(string $lastContractChange): Documentation
    {
        $this->lastContractChange = $lastContractChange;

        return $this;
    }

    public function setBalanceSheet(string $balanceSheet): Documentation
    {
        $this->balanceSheet = $balanceSheet;

        return $this;
    }

    public function setPowerOfAttorney(string $powerOfAttorney): Documentation
    {
        $this->powerOfAttorney = $powerOfAttorney;

        return $this;
    }

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
