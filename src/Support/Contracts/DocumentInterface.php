<?php

namespace WeDevBr\Bankly\Support\Contracts;

use Illuminate\Contracts\Support\Arrayable;

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
interface DocumentInterface extends Arrayable
{
    /**
     * @param string $path
     * @return DocumentInterface
     */
    public function setFilePath(string $path): DocumentInterface;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @return string|bool
     */
    public function getFileContents(): string|bool;

    /**
     * @param string $name
     * @return DocumentInterface
     */
    public function setFieldName(string $name): DocumentInterface;

    /**
     * @param string $provider
     * @return DocumentInterface
     */
    public function setProvider(string $provider): DocumentInterface;

    /**
     * @param string|null $encrypted
     * @return DocumentInterface
     */
    public function setEncrypted(string $encrypted = null): DocumentInterface;

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

    /**
     * @return string
     */
    public function getProvider(): string;

    /**
     * @return array
     */
    public function getProviderMetadata(): array;
}
