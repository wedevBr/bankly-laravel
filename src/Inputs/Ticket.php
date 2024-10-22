<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Open Finance Ticket class
 *
 * PHP version 8.0|8.1
 *
 * @author    Judson Bandeira <judsonmelobandeira@gmail.com>
 * @copyright 2024 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class Ticket implements Arrayable
{
    protected string $requestUri;

    protected string $clientId;

    protected string $documentNumber;

    public function setRequestUri(string $requestUri): Ticket
    {
        $this->requestUri = $requestUri;

        return $this;
    }

    public function setClientId(string $clientId): Ticket
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function setDocumentNumber(string $documentNumber): Ticket
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    public function toArray(): array
    {
        return [
            'requestUri' => $this->requestUri,
            'clientId' => $this->clientId,
            'documentNumber' => $this->documentNumber,
        ];
    }
}
