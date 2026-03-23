<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Inputs\DocumentAnalysisCorporationBusiness;
use WeDevBr\Bankly\Support\Contracts\DocumentInterface;

class BanklyKyc extends BaseHttpClient
{
    /**
     * @throws RequestException
     */
    public function documentAnalysis(
        string $documentNumber,
        DocumentInterface $document,
        ?string $correlationId = null
    ): mixed {
        return $this->post(
            "/document-analysis/{$documentNumber}/deepface",
            $document->toArray(),
            $correlationId,
            true,
            true,
            $document
        );
    }

    /**
     * @throws RequestException
     */
    public function documentAnalysisCorporationBusiness(
        string $documentNumber,
        DocumentAnalysisCorporationBusiness $document,
        ?string $correlationId = null
    ): mixed {
        return $this->put(
            "/document-analysis/{$documentNumber}/corporation-business",
            $document->toArray(),
            $correlationId,
            true,
            true,
            $document
        );
    }

    /**
     * @throws RequestException
     */
    public function getDocumentAnalysis(
        string $documentNumber,
        array $tokens = [],
        string $resultLevel = 'ONLY_STATUS',
        ?string $correlationId = null
    ): mixed {
        $query = collect($tokens)
            ->map(fn ($token) => "token={$token}")
            ->concat(["resultLevel={$resultLevel}"])
            ->implode('&');

        return $this->get("/document-analysis/{$documentNumber}", $query, $correlationId);
    }

    /**
     * Cria o processo de selfie no fluxo Unico IDCloud e retorna a URL de redirecionamento.
     *
     * @throws RequestException
     */
    public function createSelfieProcess(string $documentNumber, ?string $correlationId = null): mixed
    {
        return $this->post('/documents/create-process', [
            'documentNumber' => $documentNumber,
            'documentType' => 'SELFIE',
            'documentSide' => '',
        ], $correlationId, true);
    }
}
