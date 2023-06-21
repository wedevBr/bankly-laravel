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
 * @see https://docs.bankly.com.br/docs/seguranca-da-informacao
 */
trait Rest
{
    /** @var array */
    protected array $headers = [];

    /** @var string */
    protected string $apiUrl;

    /** @var string */
    protected string $apiVersion = '1';

    /** @var ?string */
    protected ?string $mtlsCert;

    /** @var ?string */
    protected ?string $mtlsKey;

    /** @var string */
    protected ?string $mtlsPassphrase;

    /** @var ?string */
    private ?string $token = null;

    /**
     * Bankly constructor.
     *
     * @param null|string $mtlsPassphrase
     */
    public function __construct(string $mtlsPassphrase = null)
    {
        $this->headers = ['api-version' => $this->apiVersion];

        $this->apiUrl = config('bankly')['api_url'];
        $this->mtlsPassphrase = $mtlsPassphrase;
        $this->mtlsCert = config('bankly')['mtls_cert_path'] ?? null;
        $this->mtlsKey = config('bankly')['mtls_key_path'] ?? null;
    }

    /**
     * Set the cert.crt file path
     * @param string $path
     * @return self
     */
    public function setCertPath(string $path)
    {
        $this->mtlsCert = $path;
        return $this;
    }

    /**
     * Set the cert.pem file path
     * @param string $path
     * @return self
     */
    public function setKeyPath(string $path)
    {
        $this->mtlsKey = $path;
        return $this;
    }

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
     * @param string $endpoint
     * @return bool
     */
    protected function requireCorrelationId(string $endpoint)
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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $endpoint
     * @param array|string|null $query
     * @param null $correlation_id
     * @param bool $responseJson
     * @return array|mixed
     */
    protected function get(string $endpoint, $query = null, $correlation_id = null, bool $responseJson = true)
    {
        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4()->toString();
        }

        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->getHeaders(['x-correlation-id' => $correlation_id]));

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        $request = $request->get($this->getFinalUrl($endpoint), $query)
            ->throw();

        return ($responseJson) ? $request->json() : $request;
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @param string|null $correlationId
     * @param bool $asJson
     * @return array|mixed
     */
    public function post(string $endpoint, array $body = [], string $correlationId = null, bool $asJson = false)
    {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $headers = $this->getHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($headers)
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
     * @param string|null $correlationId
     * @param bool $asJson
     * @param bool $attachment
     * @param DocumentAnalysis|null $document
     * @return array|mixed
     */
    public function put(
        string $endpoint,
        array $body = [],
        string $correlationId = null,
        bool $asJson = false,
        bool $attachment = false,
        DocumentAnalysis $document = null
    ) {
        if (is_null($correlationId) && $this->requireCorrelationId($endpoint)) {
            $correlationId = Uuid::uuid4()->toString();
        }

        $headers = $this->getHeaders([
            'api-version' => $this->apiVersion,
            'x-correlation-id' => $correlationId
        ]);

        $bodyFormat = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();

        $request = Http::withToken($token)
            ->withHeaders($headers)
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
     * @param array|null $body
     * @param string|null $correlation_id
     * @param bool $asJson
     * @return array|mixed
     */
    protected function patch(
        string $endpoint,
        array $body = [],
        string $correlation_id = null,
        bool $asJson = false
    ) {
        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4()->toString();
        }

        $body_format = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->getHeaders(['x-correlation-id' => $correlation_id]))
            ->bodyFormat($body_format);

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
     * @param array|null $body
     * @return array|mixed
     */
    protected function delete(string $endpoint, array $body = [])
    {
        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->getHeaders($this->headers));

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        return $request->delete($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }


    /**
     * @param string $endpoint
     * @param array|null $body
     * @param string|null $correlation_id
     * @param bool $asJson
     * @param bool $attachment
     * @param DocumentAnalysis|null $document
     * @return array|mixed
     */
    protected function postDocument(
        string $endpoint,
        array $body = [],
        string $correlation_id = null,
        bool $asJson = false,
        bool $attachment = false,
        DocumentAnalysis $document = null
    ) {
        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4()->toString();
        }

        $body_format = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? Auth::login()->getToken();
        $request = Http::withToken($token)
            ->withHeaders($this->getHeaders(['x-correlation-id' => $correlation_id]))
            ->bodyFormat($body_format);

        if ($this->mtlsCert && $this->mtlsKey && $this->mtlsPassphrase) {
            $request = $this->setRequestMtls($request);
        }

        if ($attachment) {
            $request->attach($document->getFieldName(), $document->getFileContents(), $document->getFileName());
        }

        return $request->post($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * Add cert options to request
     *
     * @param PendingRequest $request
     * @return PendingRequest
     */
    public function setRequestMtls(PendingRequest $request): PendingRequest
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
    public function getFinalUrl(string $endpoint): string
    {
        if (is_null($this->apiUrl)) {
            $this->apiUrl = config('bankly')['api_url'];
        }

        return $this->apiUrl . $endpoint;
    }

    /**
     * @param string $passPhrase
     * @return self
     */
    public function setPassphrase(string $passPhrase): self
    {
        $this->mtlsPassphrase = $passPhrase;
        return $this;
    }

    /**
     * @param array $headers
     * @return array|string[]
     */
    public function getHeaders(array $headers = []): array
    {
        $default_headers = $this->headers;

        if (count($headers) > 0) {
            $this->headers = array_merge($headers, $default_headers);
        }

        return $this->headers;
    }
}
