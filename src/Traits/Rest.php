<?php

namespace WeDevBr\Bankly\Traits;

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
    protected $headers = [];

    /** @var string */
    protected $apiUrl;

    /** @var string */
    protected $apiVersion = '1.0';

    /**
     * @param string $apiUrl
     * @return self
     */
    public function setApiUrl(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @param string $apiVersion
     * @return self
     */
    public function setApiVersion(string $apiVersion)
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @param array $header
     * @return void
     */
    public function setHeaders($header)
    {
        $this->headers = array_merge($this->headers, $header);
    }

    /**
     * @param string $endpoint
     * @return bool
     */
    private function requireCorrelationId(string $endpoint)
    {
        $not_required_endpoints = [
            '/banklist',
            '/connect/token'
        ];

        return !in_array($endpoint, $not_required_endpoints);
    }

    /**
     * @param string $endpoint
     * @param array|string|null $query
     * @param string|null $correlationId
     * @return array|mixed
     * @throws RequestException
     */
    public function get(string $endpoint, $query = null, $correlationId = null)
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'API-Version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $token = Auth::login()->getToken();
        return Http::withToken($token)
            ->withHeaders($this->headers)
            ->get($this->getFinalUrl($endpoint), $query)
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
    private function post(string $endpoint, array $body = [], $correlationId = null, bool $asJson = false)
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'API-Version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = Auth::login()->getToken();

        return Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat)
            ->post($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param string|null $correlationId
     * @param bool $asJson
     * @param bool $attachment
     * @param DocumentAnalysis $document
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
            'API-Version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat);

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
        array $body = [],
        $correlationId = null,
        bool $asJson = false
    ) {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $this->setHeaders([
            'API-Version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($this->headers)
            ->bodyFormat($bodyFormat);

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
    private function delete(string $endpoint)
    {
        $token = Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->headers);

        return $request->delete($this->getFinalUrl($endpoint))
            ->throw()
            ->json();
    }

    /**
     * @param string $endpoint
     * @return string
     */
    private function getFinalUrl(string $endpoint)
    {
        if (is_null($this->apiUrl)) {
            $this->apiUrl = config('bankly')['api_url'];
        }

        return $this->apiUrl . $endpoint;
    }
}
