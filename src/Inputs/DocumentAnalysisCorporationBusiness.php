<?php

namespace WeDevBr\Bankly\Inputs;

use WeDevBr\Bankly\Support\Contracts\DocumentInterface;

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
class DocumentAnalysisCorporationBusiness implements DocumentInterface
{
    protected ?string $documentSide = null;

    protected string $documentType;

    protected string $filePath;

    protected ?string $fieldName;

    protected ?string $encrypted = null;

    protected ?string $provider = null;


    public function setDocumentSide(string $documentSide): DocumentAnalysisCorporationBusiness
    {
        $this->documentSide = $documentSide;

        return $this;
    }

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

    public function setFieldName(string $name): DocumentAnalysisCorporationBusiness
    {
        $this->fieldName = $name;

        return $this;
    }

    public function setProvider(string $provider): DocumentAnalysisCorporationBusiness
    {
        $this->provider = $provider;

        return $this;
    }

    public function setEncrypted(?string $encrypted = null): DocumentAnalysisCorporationBusiness
    {
        $this->encrypted = $encrypted;

        return $this;
    }

    public function getDocumentSide(): string
    {
        return $this->documentSide;
    }

    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function getFileName(): string
    {
        return basename($this->filePath);
    }

    public function getFileContents(): string|bool
    {
        return file_get_contents($this->filePath);
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getEncrypted(): string
    {
        return $this->encrypted;
    }

    public function getProviderMetadata(): array
    {
        if ($this->documentType === 'SELFIE') {
            return [
                'isLastDocument' => true,
                'encrypted' => $this->getEncrypted(),
            ];
        }

        return [];
    }

    public function toArray(): array
    {
        $array = [
            'documentType' => $this->getDocumentType(),
        ];

        $providerMetadata = $this->getProviderMetadata();

        if (! empty($providerMetadata)) {
            $array['providerMetadata'] = json_encode($providerMetadata);
        }

        return $array;
    }
}
