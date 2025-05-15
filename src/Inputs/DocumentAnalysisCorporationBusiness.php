<?php

namespace WeDevBr\Bankly\Inputs;

/**
 * DocumentAnalysisCorporationBusiness class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Judson Bandeira <judsonmelobandeira@gmail.com>
 * @copyright 2025 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class DocumentAnalysisCorporationBusiness
{
    protected string $documentType;

    protected string $filePath;

    public function setDocumentType(string $documentType): DocumentAnalysisCorporationBusiness
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function setFilePath(string $path): DocumentAnalysisCorporationBusiness
    {
        $this->filePath = $path;

        return $this;
    }
}
