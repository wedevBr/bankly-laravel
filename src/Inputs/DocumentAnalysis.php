<?php

namespace WeDevBr\Bankly\Inputs;

use WeDevBr\Bankly\Support\Contracts\DocumentInterface;

/**
 * DocumentAnalysis class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class DocumentAnalysis implements DocumentInterface
{
    /** @var string */
    protected $documentSide;

    /** @var string */
    protected $documentType;

    /** @var string */
    protected $filePath;

    /** @var string */
    protected $fieldName;

    /**
     * @param string $documentSide
     * @return DocumentAnalysis
     */
    public function setDocumentSide(string $documentSide): DocumentAnalysis
    {
        $this->documentSide = $documentSide;
        return $this;
    }

    /**
     * @param string $documentType
     * @return DocumentAnalysis
     */
    public function setDocumentType(string $documentType): DocumentAnalysis
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @param string $path
     * @return DocumentAnalysis
     */
    public function setFilePath(string $path): DocumentAnalysis
    {
        $this->filePath = $path;
        return $this;
    }

    /**
     * @param string $name
     * @return DocumentAnalysis
     */
    public function setFieldName(string $name): DocumentAnalysis
    {
        $this->fieldName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentSide(): string
    {
        return $this->documentSide;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return basename($this->filePath);
    }

    /**
     * @return mixed
     */
    public function getFileContents()
    {
        return file_get_contents($this->filePath);
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
