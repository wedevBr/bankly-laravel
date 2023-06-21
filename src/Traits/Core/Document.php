<?php

namespace WeDevBr\Bankly\Traits\Core;

use WeDevBr\Bankly\Inputs\DocumentAnalysis;
use WeDevBr\Bankly\Support\Contracts\DocumentInterface;

/**
 * @see https://docs.bankly.com.br/docs/id-one
 */
trait Document
{
    /**
     * @param string $documentNumber
     * @param DocumentInterface $document
     * @param string|null $correlationId
     * @return array|mixed
     */
    public function documentAnalysis(
        string $documentNumber,
        DocumentInterface $document,
        string $correlationId = null
    ) {
        return $this->postDocument(
            "/document-analysis/{$documentNumber}/deepface",
            [
                'documentType' => $document->getDocumentType(),
                'documentSide' => $document->getDocumentSide(),
                'provider' => $document->getProvider(),
                'providerMetadata' => json_encode($document->getProviderMetadata())
            ],
            $correlationId,
            true,
            true,
            $document
        );
    }

    /**
     * @param string $documentNumber
     * @param array $tokens
     * @param string $resultLevel
     * @param string $correlationId
     * @return array|mixed
     */
    public function getDocumentAnalysis(
        string $documentNumber,
        array $tokens = [],
        string $resultLevel = 'ONLY_STATUS',
        string $correlationId = null
    ) {
        $query = collect($tokens)
            ->map(function ($token) {
                return "token={$token}";
            })
            ->concat(["resultLevel={$resultLevel}"])
            ->implode('&');

        return $this->get(
            "/document-analysis/{$documentNumber}",
            $query,
            $correlationId
        );
    }
}
