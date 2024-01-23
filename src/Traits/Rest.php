<?php

namespace WeDevBr\Bankly\Traits;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;
use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Inputs\DocumentAnalysis;

/**
 * Trait Rest
 *
 * @author Rafael Teixeira <rafael.teixeira@wedev.software>
 */
trait Rest
{
    protected array $headers = [];

    protected mixed $apiUrl = null;

    protected string $apiVersion = '1';

    protected ?string $token = null;

    public function setApiUrl(string $apiUrl): self
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @param  array  $header
     */
    public function setHeaders($header): void
    {
        $this->headers = array_merge($this->headers, $header);
    }

    protected function requireCorrelationId(string $endpoint): bool
    {
        $not_required_endpoints = [
            '/banklist',
            '/connect/token',
        ];

        return ! in_array($endpoint, $not_required_endpoints);
    }

    /**
     * Set token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Return token
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param  array|string|null  $query
     * @param  string|null  $correlationId
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function get(string $endpoint, $query = null, $correlationId = null): mixed
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId,
        ]);

        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->headers);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        return $request->get($this->getFinalUrl($endpoint), $query)
            ->throw()
            ->json();
    }

    /**
     * @param  string|null  $correlationId
     * @return array|mixed
     *
     * @throws RequestException
     */
    protected function post(string $endpoint, array $body = [], $correlationId = null, bool $asJson = false): mixed
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId,
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        return $request->post($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @param  null  $correlationId
     * @return array|mixed
     *
     * @throws RequestException
     */
    protected function put(
        string $endpoint,
        array $body = [],
        $correlationId = null,
        bool $asJson = false,
        bool $attachment = false,
        ?DocumentAnalysis $document = null
    ) {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId,
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        if ($attachment && ! is_null($document)) {
            $request->attach($document->getFieldName(), $document->getFileContents(), $document->getFileName());
        }

        return $request->put($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    protected function patch(
        string $endpoint,
        array $body = [],
        ?string $correlationId = null,
        bool $asJson = false
    ) {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId,
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        return $request->patch($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * Http delete method.
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    protected function delete(string $endpoint, array $body = []): mixed
    {
        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->headers);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        return $request->delete($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * Add cert options to request
     */
    protected function setRequestMtls(PendingRequest $request): PendingRequest
    {
        return $request->withOptions([
            'cert' => $this->mtlsCert,
            'ssl_key' => [$this->mtlsKey, $this->mtlsPassphrase],
        ]);
    }

    protected function getFinalUrl(string $endpoint): string
    {
        if (is_null($this->apiUrl)) {
            $this->apiUrl = config('bankly')['api_url'];
        }

        return $this->apiUrl.$endpoint;
    }
}
