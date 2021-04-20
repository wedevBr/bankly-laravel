<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;
use TypeError;
use WeDevBr\Bankly\Inputs\Customer;
use WeDevBr\Bankly\Inputs\DocumentAnalysis;
use WeDevBr\Bankly\Support\Contracts\CustomerInterface;
use WeDevBr\Bankly\Support\Contracts\DocumentInterface;
use WeDevBr\Bankly\Types\Pix\PixEntries;
use WeDevBr\Bankly\Types\VirtualCard\VirtualCard;

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
    private $headers;

    /**
     * Bankly constructor.
     * @param null|string $client_secret provided by Bankly Staff
     * @param null|string $client_id provided by Bankly Staff
     */
    public function __construct($client_secret = null, $client_id = null)
    {
        $this->api_url = config('bankly')['api_url'];
        $this->login_url = config('bankly')['login_url'];
        $this->setClientCredentials(['client_secret' => $client_secret, 'client_id' => $client_id]);
        $this->headers = ['API-Version' => $this->api_version];
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
    public function getBankList()
    {
        return $this->get('/banklist');
    }

    /**
     * Retrieve your balance account
     * @param string $branch
     * @param string $account
     * @return array|mixed
     * @throws RequestException
     * @note If you have a RequestException on this endpoint in staging environment, please use getAccount() method instead.
     */
    public function getBalance(string $branch, string $account)

    {
        return $this->get('/account/balance', [
            'branch' => $branch,
            'account' => $account
        ]);
    }

    /**
     * @param string $account
     * @param string $includeBalance
     * @return array|mixed
     * @throws RequestException
     * @note This method on this date (2020-10-21) works only on staging environment. Contact Bankly/Acesso for more details
     */
    public function getAccount(string $account, string $includeBalance = 'true')
    {
        return $this->get('/accounts/' . $account, [
            'includeBalance' => $includeBalance,
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
    public function getStatement(
        $branch,
        $account,
        $offset = 1,
        $limit = 20,
        string $details = 'true',
        string $detailsLevelBasic = 'true'
    ) {
        return $this->get('/account/statement', array(
            'branch' => $branch,
            'account' => $account,
            'offset' => $offset,
            'limit' => $limit,
            'details' => $details,
            'detailsLevelBasic' => $detailsLevelBasic
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
     * @note This endpoint has been deprecated for some clients.
     * You need to check with Acesso/Bankly if your environment has different parameters also.
     * The response of this request does not have a default interface between environments.
     * Pay attention when use this in your project.
     */
    public function getEvents(
        string $branch,
        string $account,
        int $page = 1,
        int $pagesize = 20,
        string $include_details = 'true'
    ) {
        return $this->get(
            '/events',
            [
                'branch' => $branch,
                'account' => $account,
                'page' => $page,
                'pageSize' => $pagesize,
                'includeDetails' => $include_details

            ]
        );
    }

    /**
     * @param int $amount
     * @param string $description
     * @param array $sender
     * @param array $recipient
     * @param string|null $correlation_id
     * @return array|mixed
     * @throws RequestException
     */
    public function transfer(
        int $amount,
        string $description,
        array $sender,
        array $recipient,
        string $correlation_id = null
    ) {
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
            $correlation_id,
            true
        );
    }

    /**
     * Get transfer funds from an account
     * @param string $branch
     * @param string $account
     * @param int $pageSize
     * @param string|null $nextPage
     * @return array|mixed
     * @throws RequestException
     */
    public function getTransferFunds(string $branch, string $account, int $pageSize = 10, string $nextPage = null)
    {
        $queryParams = [
            'branch' => $branch,
            'account' => $account,
            'pageSize' => $pageSize
        ];
        if ($nextPage) {
            $queryParams['nextPage'] = $nextPage;
        }
        return $this->get('/fund-transfers', $queryParams);
    }

    /**
     * Get Transfer Funds By Authentication Code
     * @param string $branch
     * @param string $account
     * @param string $authenticationCode
     * @return array|mixed
     * @throws RequestException
     */
    public function findTransferFundByAuthCode(string $branch, string $account, string $authenticationCode)
    {
        $queryParams = [
            'branch' => $branch,
            'account' => $account
        ];
        return $this->get('/fund-transfers/' . $authenticationCode, $queryParams);
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
     * @param string $documentNumber
     * @param DocumentAnalysis $document
     * @param string $correlationId
     * @return array|mixed
     * @throws RequestException
     */
    public function documentAnalysis(
        string $documentNumber,
        $document,
        string $correlationId = null
    ) {
        if (!$document instanceof DocumentInterface) {
            throw new TypeError('The document must be an instance of DocumentInterface');
        }

        return $this->put(
            "/document-analysis/{$documentNumber}",
            [
                'documentType' => $document->getDocumentType(),
                'documentSide' => $document->getDocumentSide(),
            ],
            $correlationId,
            true,
            true,
            $document
        );
    }

    /**
     * @param string $documentNumber
     * @param array $tokens
     * @param string $resultLevel
     * @param string $correlationId
     * @return array|mixed
     */
    public function getDocumentAnalysis(
        string $documentNumber,
        array $tokens = [],
        string $resultLevel = 'ONLY_STATUS',
        string $correlationId = null
    ) {
        $query = collect($tokens)
            ->map(function ($token) {
                return "token={$token}";
            })
            ->concat(["resultLevel={$resultLevel}"])
            ->implode('&');

        return $this->get(
            "/document-analysis/{$documentNumber}",
            $query,
            $correlationId
        );
    }

    /**
     * @param string $documentNumber
     * @param Customer $customer
     * @param string $correlationId
     * @return array|mixed
     * @throws RequestException
     */
    public function customer(
        string $documentNumber,
        $customer,
        string $correlationId = null
    ) {
        if (!$customer instanceof CustomerInterface) {
            throw new TypeError('The customer must be an instance of CustomerInterface');
        }

        return $this->put("/customers/{$documentNumber}", $customer->toArray(), $correlationId);
    }

    /**
     * Validate of boleto or dealership
     *
     * @param string $code - Digitable line
     * @param string $correlationId
     * @return array|mixed
     * @throws RequestException
     */
    public function paymentValidate(string $code, string $correlationId)
    {
        return $this->post('/bill-payment/validate', ['code' => $code], $correlationId, true);
    }

    /**
     * Confirmation of payment of boleto or dealership
     *
     * @param BillPayment $billPayment
     * @param string $correlationId
     * @return array|mixed
     */
    public function paymentConfirm(
        BillPayment $billPayment,
        string $correlationId
    ) {
        return $this->post('/bill-payment/confirm', $billPayment->toArray(), $correlationId, true);
    }

    /**
     * Create a new PIX key link with account.
     *
     * @param PixEntries $pixEntries
     * @return array|mixed
     */
    public function registerPixKey(PixEntries $pixEntries)
    {
        return $this->post('/pix/entries', [
            'addressingKey' => $pixEntries->addressingKey->toArray(),
            'account' => $pixEntries->account->toArray(),
        ], null, true);
    }

    /**
     * Gets the list of address keys linked to an account.
     *
     * @param string $accountNumber
     * @return array|mixed
     */
    public function getPixAddressingKeys(string $accountNumber)
    {
        return $this->get("/accounts/$accountNumber/addressing-keys");
    }

    /**
     * Gets details of the account linked to an addressing key.
     *
     * @param string $documentNumber
     * @param string $addressinKeyValue
     * @return array|mixed
     */
    public function getPixAddressingKeyValue(string $documentNumber, string $addressinKeyValue)
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);
        return $this->get("/pix/entries/$addressinKeyValue");
    }

    /**
     * Delete a key link with account.
     *
     * @param string $addressingKeyValue
     * @return array|mixed
     */
    public function deletePixAddressingKeyValue(string $addressingKeyValue)
    {
        return $this->delete("/pix/entries/$addressingKeyValue");
    }

    /**
     * @param string $endpoint
     * @param array|string|null $query
     * @param null $correlation_id
     * @return array|mixed
     * @throws RequestException
     */
    private function get(string $endpoint, $query = null, $correlation_id = null)
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
     * Create a new virtual card
     *
     * @param VirtualCard $virtualCard
     * @return array|mixed
     * @throws RequestException
     */
    public function virtualCard(VirtualCard $virtualCard)
    {
        return $this->post('/cards/virtual', $virtualCard->toArray(), null, true);
    }

    /**
     * @param string $endpoint
     * @param array|null $body
     * @param string|null $correlation_id
     * @param bool $asJson
     * @return array|mixed
     * @throws RequestException
     */
    private function post(string $endpoint, array $body = null, string $correlation_id = null, bool $asJson = false)
    {
        if (now()->unix() > $this->token_expiry || !$this->token) {
            $this->auth();
        }

        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4()->toString();
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
     * @param string $endpoint
     * @param array|null $body
     * @param string|null $correlation_id
     * @param bool $asJson
     * @param bool $attachment
     * @param DocumentAnalysis $document
     * @param string $fieldName
     * @return array|mixed
     * @throws RequestException
     */
    private function put(
        string $endpoint,
        array $body = [],
        string $correlation_id = null,
        bool $asJson = false,
        bool $attachment = false,
        DocumentAnalysis $document = null
    ) {
        if (now()->unix() > $this->token_expiry || !$this->token) {
            $this->auth();
        }

        if (is_null($correlation_id) && $this->requireCorrelationId($endpoint)) {
            $correlation_id = Uuid::uuid4()->toString();
        }

        $body_format = $asJson ? 'json' : 'form_params';

        $request = Http
            ::withToken($this->token)
            ->withHeaders($this->getHeaders(['x-correlation-id' => $correlation_id]))
            ->bodyFormat($body_format);

        if ($attachment) {
            $request->attach($document->getFieldName(), $document->getFileContents(), $document->getFileName());
        }

        return $request->put($this->getFinalUrl($endpoint), $body)
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
        if (now()->unix() > $this->token_expiry || !$this->token) {
            $this->auth();
        }

        $request = Http::withToken($this->token)
            ->withHeaders($this->getHeaders($this->headers));

        return $request->delete($this->getFinalUrl($endpoint))
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
    private function getHeaders($headers = [])
    {
        $default_headers = $this->headers;

        if (count($headers) > 0) {
            $default_headers = array_merge($headers, $default_headers);
        }

        return $default_headers;
    }

    /**
     * @param array $header
     * @return void
     */
    private function setHeaders($header)
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
     * @return string
     */
    private function getFinalUrl(string $endpoint)
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
