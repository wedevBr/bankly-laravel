<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;

/**
 * Class Bankly
 * @author Adeildo Amorim <adeildo@wedev.software>
 * @package WeDevBr\Bankly
 */
class Bankly
{
    public $api_url;
    public $login_url;
    private $client_id;
    private $client_secret;
    private $token_expiry = 0;
    private $token = null;
    private $api_version = '1.0';

    /**
     * Bankly constructor.
     * @param string $client_secret provided by Bankly Staff
     * @param string $client_id provided by Bankly Staff
     */
    public function __construct($client_secret = null, $client_id = null)
    {
        $this->api_url = config('bankly')['api_url'];
        $this->login_url = config('bankly')['login_url'];
        $this->setClientCredentials(['client_secret' => $client_secret, 'client_id' => $client_id]);
    }

    /**
     * @param array|null $credentials
     * @return $this
     */
    public function setClientCredentials(array $credentials = null)
    {
        $this->client_secret = $credentials['client_secret'] ?? config('bankly')['client_secret'];
        $this->client_id = $credentials['client_id'] ?? config('bankly')['client_id'];
        return $this;
    }

    /**
     * @return array|mixed
     * @throws RequestException
     */
    final public function getBankList()
    {
        return $this->get('/bankList');
    }

    /**
     * @param string $branch
     * @param string $account
     * @return array|mixed
     * @throws RequestException
     */
    final public function getBalance(string $branch, string $account)
    {
        return $this->get('/account/balance', [
            'branch' => $branch,
            'account' => $account
        ]);
    }

    /**
     * @param $branch
     * @param $account
     * @param int $offset
     * @param int $limit
     * @param string $details
     * @param string $detailsLevelBasic
     * @return array|mixed
     * @throws RequestException
     */
    final public function getStatement(
        $branch,
        $account,
        $offset = 1,
        $limit = 20,
        $details = 'true',
        $detailsLevelBasic = 'true'
    ) {
        return $this->get('/account/statement', array(
            'branch' => $branch,
            'account' => $account,
            'offset' => $offset,
            'limit' => $limit,
            'details' => (string) $details,
            'detailsLevelBasic' => (string) $detailsLevelBasic
        ));
    }

    /**
     * @param string $branch
     * @param string $account
     * @param int $page
     * @param int $pagesize
     * @param string $include_details
     * @return array|mixed
     * @throws RequestException
     */
    public function getEvents(
        string $branch,
        string $account,
        int $page = 1,
        int $pagesize = 20,
        $include_details = 'true'
    ) {
        return $this->get(
            '/events',
            [
                'Branch' => $branch,
                'Account' => $account,
                'Page' => $page,
                'Pagesize' => $pagesize,
                'IncludeDetails' => (string) $include_details
            ]
        );
    }

    /**
     * @param string $amount
     * @param string $description
     * @param array $sender
     * @param array $recipient
     * @return array|mixed
     * @throws RequestException
     */
    public function transfer(string $amount, string $description, array $sender, array $recipient)
    {
        if ($sender['bankCode']) {
            unset($sender['bankCode']);
        }

        return $this->post(
            '/fund-transfers',
            [
                'amount' => $amount,
                'description' => $description,
                'sender' => $sender,
                'recipient' => $recipient
            ],
            null,
            true
        );
    }

    /**
     * @param string $branch
     * @param string $account
     * @param string $authentication_id
     * @return array|mixed
     * @throws RequestException
     */
    public function getTransferStatus(string $branch, string $account, string $authentication_id)
    {
        return $this->get('/fund-transfers/' . $authentication_id . '/status', [
            'branch' => $branch,
            'account' => $account
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $query
     * @param null $correlation_id
     * @return array|mixed
     * @throws RequestException
     */
    private function get(string $endpoint, array $query = null, $correlation_id = null)
    {
        if (now()->unix() > $this->token_expiry || !$this->token) {
            $this->auth();
        }

        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4()->toString();
        }

        return Http::withToken($this->token)
            ->withHeaders($this->getHeaders(['x-correlation-id' => $correlation_id]))
            ->get($this->getFinalUrl($endpoint), $query)
            ->throw()
            ->json();
    }

    /**
     * @param string $endpoint
     * @param array|null $body
     * @param null $correlation_id
     * @param bool $asJson
     * @return array|mixed
     * @throws RequestException
     */
    private function post($endpoint, array $body = null, $correlation_id = null, $asJson = false)
    {
        if (now()->unix() > $this->token_expiry || !$this->token) {
            $this->auth();
        }

        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4();
        }

        $body_format = $asJson ? 'json' : 'form_params';

        return Http
            ::withToken($this->token)
            ->withHeaders($this->getHeaders(['x-correlation-id' => $correlation_id]))
            ->bodyFormat($body_format)
            ->post($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @param string $version API version
     * @return $this
     */
    private function setApiVersion($version = '1.0')
    {
        $this->api_version = $version;
        return $this;
    }

    /**
     * @param array $headers
     * @return array|string[]
     */
    final private function getHeaders($headers = [])
    {
        $default_headers = [
            'API-Version' => $this->api_version
        ];

        if (count($headers) > 0) {
            $default_headers = array_merge($headers, $default_headers);
        }

        return $default_headers;
    }

    /**
     * @param string $endpoint
     * @return bool
     */
    final private function requireCorrelationId($endpoint)
    {
        $not_required_endpoints = [
            '/bankList',
            '/connect/token'
        ];

        return !in_array($endpoint, $not_required_endpoints);
    }

    /**
     * @param $endpoint
     * @return string
     */
    final private function getFinalUrl($endpoint)
    {
        return $this->api_url . $endpoint;
    }

    /**
     * Do authentication
     * @param string $grant_type Default sets to 'client_credentials'
     * @throws RequestException
     */
    private function auth($grant_type = 'client_credentials'): void
    {
        //TODO: Add auth for username and password
        $body = [
            'grant_type' => $grant_type,
            'client_secret' => $this->client_secret,
            'client_id' => $this->client_id
        ];

        $response = Http::asForm()->post($this->login_url, $body)->throw()->json();
        $this->token = $response['access_token'];
        $this->token_expiry = now()->addSeconds($response['expires_in'])->unix();
    }
}
