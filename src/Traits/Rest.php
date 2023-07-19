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
 * @author Rafael Teixeira <rafael.teixeira@wedev.software>
 * @package WeDevBr\Bankly
 */
trait Rest
{
    /** @var array */
    protected array $headers = [];

    /** @var string */
    protected string $apiUrl;

    /** @var string */
    protected string $apiVersion = '1';

    /** @var string */
    protected string $mtlsCert;

    /** @var string */
    protected string $mtlsKey;

    /** @var string */
    protected string $mtlsPassphrase;

    /** @var string|null */
    private ?string $token = null;

    /**
     * @param string $apiUrl
     * @return self
     */
    public function setApiUrl(string $apiUrl): self
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @param string $apiVersion
     * @return self
     */
    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @param array $header
     * @return void
     */
    public function setHeaders($header): void
    {
        $this->headers = array_merge($this->headers, $header);
    }

    /**
     * @param string $endpoint
     * @return bool
     */
    private function requireCorrelationId(string $endpoint): bool
    {
        $not_required_endpoints = [
            '/banklist',
            '/connect/token'
        ];

        return !in_array($endpoint, $not_required_endpoints);
    }

    /**
     * Set token
     *
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Return token
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $endpoint
     * @param array|string|null $query
     * @param string|null $correlationId
     * @return array|mixed
     * @throws RequestException
     */
    public function get(string $endpoint, $query = null, $correlationId = null): mixed
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
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
     * @param string $endpoint
     * @param array $body
     * @param string|null $correlationId
     * @param bool $asJson
     * @return array|mixed
     * @throws RequestException
     */
    private function post(string $endpoint, array $body = [], $correlationId = null, bool $asJson = false): mixed
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
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
     * @param string $endpoint
     * @param array $body
     * @param null $correlationId
     * @param bool $asJson
     * @param bool $attachment
     * @param DocumentAnalysis|null $document
     * @return array|mixed
     * @throws RequestException
     */
    private function put(
        string $endpoint,
        array $body = [],
        $correlationId = null,
        bool $asJson = false,
        bool $attachment = false,
        DocumentAnalysis $document = null
    ) {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        if ($attachment && !is_null($document)) {
            $request->attach($document->getFieldName(), $document->getFileContents(), $document->getFileName());
        }

        return $request->put($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param string|null $correlationId
     * @param bool $asJson
     * @return array|mixed
     * @throws RequestException
     */
    private function patch(
        string $endpoint,
        array  $body = [],
        string $correlationId = null,
        bool   $asJson = false
    ) {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
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
     * @param string $endpoint
     * @return array|mixed
     * @throws RequestException
     */
    private function delete(string $endpoint): mixed
    {
        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->headers);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        return $request->delete($this->getFinalUrl($endpoint))
            ->throw()
            ->json();
    }

    /**
     * Add cert options to request
     *
     * @param PendingRequest $request
     * @return PendingRequest
     */
    private function setRequestMtls(PendingRequest $request): PendingRequest
    {
        return $request->withOptions([
            'cert' => $this->mtlsCert,
            'ssl_key' => [$this->mtlsKey, $this->mtlsPassphrase]
        ]);
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function getFinalUrl(string $endpoint): string
    {
        if (is_null($this->apiUrl)) {
            $this->apiUrl = config('bankly')['api_url'];
        }

        return $this->apiUrl . $endpoint;
    }
}
