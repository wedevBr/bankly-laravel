<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\CreditAcceptanceInterface;
use WeDevBr\Bankly\Support\Contracts\CreditAnalysisInterface;
use WeDevBr\Bankly\Support\Contracts\CreditLimitAcceptanceInterface;
use WeDevBr\Bankly\Support\Contracts\CreditPreAnalysisInterface;
use WeDevBr\Bankly\Support\Contracts\CreditReanalysisInterface;

class BanklyCredit extends BaseHttpClient
{
    public function createCreditAnalysis(CreditAnalysisInterface $creditAnalysis, ?string $correlationId = null): mixed
    {
        return $this->post('/cards/credits/customers', $creditAnalysis->toArray(), $correlationId, true);
    }

    public function createPreAnalysis(CreditPreAnalysisInterface $preAnalysis, ?string $correlationId = null): mixed
    {
        return $this->post('/cards/credits/pre-analysis', $preAnalysis->toArray(), $correlationId, true);
    }

    public function acceptContractProposal(CreditAcceptanceInterface $acceptance, ?string $correlationId = null): mixed
    {
        return $this->post('/cards/credits/terms-and-conditions/acceptance', $acceptance->toArray(), $correlationId, true);
    }

    public function getContract(string $documentNumber, string $contract): mixed
    {
        return $this->get("/cards/credits/document/{$documentNumber}/contracts/{$contract}");
    }

    public function reanalyzeContract(string $documentNumber, string $contract, CreditReanalysisInterface $reanalysis, ?string $correlationId = null): mixed
    {
        return $this->put("/cards/credits/document/{$documentNumber}/contract/{$contract}/reanalyze", $reanalysis->toArray(), $correlationId, true);
    }

    public function acceptNewLimit(string $documentNumber, string $contract, CreditLimitAcceptanceInterface $acceptance, ?string $correlationId = null): mixed
    {
        return $this->put("/cards/credits/document/{$documentNumber}/contract/{$contract}/acceptance", $acceptance->toArray(), $correlationId, true);
    }
}
