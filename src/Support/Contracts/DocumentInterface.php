<?php

namespace WeDevBr\Bankly\Support\Contracts;

use WeDevBr\Bankly\Inputs\DocumentAnalysis;

/**
 * DocumentInterface interface
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
interface DocumentInterface
{
    /**
     * @param string $path
     * @return DocumentAnalysis
     */
    public function setFilePath(string $path): DocumentAnalysis;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @return void
     */
    public function getFileContents();

    /**
     * @param string $name
     * @return DocumentAnalysis
     */
    public function setFieldName(string $name): DocumentAnalysis;

    /**
     * @return string
     */
    public function getDocumentSide(): string;

    /**
     * @return string
     */
    public function getDocumentType(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;
}
