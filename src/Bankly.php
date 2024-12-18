<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;
use TypeError;
use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Inputs\BusinessCustomer;
use WeDevBr\Bankly\Inputs\DocumentAnalysis;
use WeDevBr\Bankly\Support\Contracts\CustomerInterface;
use WeDevBr\Bankly\Support\Contracts\DocumentInterface;
use WeDevBr\Bankly\Types\Customer\PaymentAccount;
use WeDevBr\Bankly\Types\Pix\PixDynamicQrCode;
use WeDevBr\Bankly\Types\Pix\PixEntries;
use WeDevBr\Bankly\Types\Pix\PixQrCodeData;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;

/**
 * Class Bankly
 *
 * @author Adeildo Amorim <adeildo@wedev.software>
 */
class Bankly
{
    public ?string $api_url;

    private ?string $mtlsCert = null;

    private ?string $mtlsKey = null;

    private ?string $mtlsPassphrase;

    private ?string $token = null;

    private string $api_version = '1';

    private array $headers;

    /**
     * Bankly constructor.
     */
    public function __construct(?string $mtlsPassphrase = null)
    {
        $this->headers = ['api-version' => $this->api_version];

        $this->api_url = config('bankly')['api_url'];
        $this->mtlsPassphrase = $mtlsPassphrase;
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

    public function setPassphrase(string $passPhrase): self
    {
        $this->mtlsPassphrase = $passPhrase;

        return $this;
    }

    /**
     * Set the cert.crt file path
     *
     * @return self
     */
    public function setCertPath(string $path): static
    {
        $this->mtlsCert = $path;

        return $this;
    }

    /**
     * Set the cert.pem file path
     *
     * @return self
     */
    public function setKeyPath(string $path): static
    {
        $this->mtlsKey = $path;

        return $this;
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getBankList($product = 'None'): mixed
    {
        return $this->get('/banklist', [
            'product' => $product,
        ]);
    }

    /**
     * Retrieve your balance account
     *
     * @return array|mixed
     *
     * @throws RequestException
     *
     * @note If you have a RequestException on this endpoint in staging environment, please use getAccount() method instead.
     */
    public function getBalance(string $branch, string $account): mixed
    {
        return $this->get('/account/balance', [
            'branch' => $branch,
            'account' => $account,
        ]);
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     *
     * @note This method on this date (2020-10-21) works only on staging environment. Contact Bankly/Acesso for more details
     */
    public function getAccount(string $account, string $includeBalance = 'true'): mixed
    {
        return $this->get('/accounts/'.$account, [
            'includeBalance' => $includeBalance,
        ]);
    }

    /**
     * Returns the income report for a given year
     *
     * @param  string|null  $year  If not informed, the previous year will be used
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getIncomeReport(string $account, ?string $year = null): mixed
    {
        return $this->get('/accounts/'.$account.'/income-report', [
            'calendar' => $year,
        ]);
    }

    /**
     * Returns the PDF of the income report for a given year in base64 format
     *
     * @param  string|null  $year  If not informed, the previous year will be used
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getIncomeReportPrint(string $account, ?string $year = null): mixed
    {
        return $this->get('/accounts/'.$account.'/income-report/print', [
            'calendar' => $year,
        ]);
    }

    /**
     * @param  int  $offset
     * @param  int  $limit
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getStatement(
        $branch,
        $account,
        $offset = 1,
        $limit = 20,
        string $details = 'true',
        string $detailsLevelBasic = 'true'
    ): mixed {
        return $this->get('/account/statement', [
            'branch' => $branch,
            'account' => $account,
            'offset' => $offset,
            'limit' => $limit,
            'details' => $details,
            'detailsLevelBasic' => $detailsLevelBasic,
        ]);
    }

    /**
     * @param  string[]  $cardProxy
     * @return array|mixed
     *
     * @throws RequestException
     *
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
        string $include_details = 'true',
        array $cardProxy = [],
        ?string $begin_date = null,
        ?string $end_date = null
    ): mixed {
        $query = [
            'branch' => $branch,
            'account' => $account,
            'page' => $page,
            'pageSize' => $pagesize,
            'includeDetails' => $include_details,
        ];

        if (! empty($cardProxy)) {
            $query['cardProxy'] = $cardProxy;
        }

        if ($begin_date) {
            $query['beginDateTime'] = $begin_date;
        }

        if ($end_date) {
            $query['endDateTime'] = $end_date;
        }

        return $this->get(
            '/events',
            $query
        );
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function transfer(
        int $amount,
        string $description,
        array $sender,
        array $recipient,
        ?string $correlation_id = null
    ): mixed {
        if ($sender['bankCode']) {
            unset($sender['bankCode']);
        }

        return $this->post(
            '/fund-transfers',
            [
                'amount' => $amount,
                'description' => $description,
                'sender' => $sender,
                'recipient' => $recipient,
            ],
            $correlation_id,
            true
        );
    }

    /**
     * Get transfer funds from an account
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getTransferFunds(string $branch, string $account, int $pageSize = 10, ?string $nextPage = null): mixed
    {
        $queryParams = [
            'branch' => $branch,
            'account' => $account,
            'pageSize' => $pageSize,
        ];
        if ($nextPage) {
            $queryParams['nextPage'] = $nextPage;
        }

        return $this->get('/fund-transfers', $queryParams);
    }

    /**
     * Get Transfer Funds By Authentication Code
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function findTransferFundByAuthCode(string $branch, string $account, string $authenticationCode): mixed
    {
        $queryParams = [
            'branch' => $branch,
            'account' => $account,
        ];

        return $this->get('/fund-transfers/'.$authenticationCode, $queryParams);
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getTransferStatus(string $branch, string $account, string $authentication_id): mixed
    {
        return $this->get('/fund-transfers/'.$authentication_id.'/status', [
            'branch' => $branch,
            'account' => $account,
        ]);
    }

    /**
     * @param  DocumentAnalysis  $document
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function documentAnalysis(
        string $documentNumber,
        DocumentInterface $document,
        ?string $correlationId = null
    ): mixed {
        $body = $document->toArray();

        return $this->postDocument(
            "/document-analysis/{$documentNumber}/deepface",
            $body,
            $correlationId,
            true,
            true,
            $document
        );
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function getDocumentAnalysis(
        string $documentNumber,
        array $tokens = [],
        string $resultLevel = 'ONLY_STATUS',
        ?string $correlationId = null
    ): mixed {
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
     * Customer register
     *
     * @return array|mixed
     *
     * @throws TypeError|RequestException
     */
    public function customer(
        string $documentNumber,
        CustomerInterface $customer,
        ?string $correlationId = null
    ): mixed {
        if (! $customer instanceof CustomerInterface) {
            throw new TypeError('The customer must be an instance of CustomerInterface');
        }

        return $this->put("/customers/{$documentNumber}", $customer->toArray(), $correlationId, true);
    }

    /**
     * Business customer register
     *
     * @return array|mixed
     *
     * @throws TypeError|RequestException
     */
    public function businessCustomer(
        string $documentNumber,
        BusinessCustomer $customer,
        ?string $correlationId = null
    ): mixed {
        return $this->put("/business/{$documentNumber}", $customer->toArray(), $correlationId, true);
    }

    /**
     * Close account
     *
     * @param  string  $reason  HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function closeAccount(string $account, string $reason = 'HOLDER_REQUEST', ?string $correlationId = null): mixed
    {
        return $this->patch('/accounts/'.$account.'/closure', [
            'reason' => $reason,
        ], $correlationId, true);
    }

    /**
     * Customer offboarding
     *
     * @param  string  $reason  HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function cancelCustomer(string $documentNumber, string $reason = 'HOLDER_REQUEST', ?string $correlationId = null): mixed
    {
        return $this->patch('/customers/'.$documentNumber.'/cancel', [
            'reason' => $reason,
        ], $correlationId, true);
    }

    /**
     * @throws RequestException
     */
    public function updateCustomer(string $documentNumber, CustomerInterface $customer, ?string $correlationId = null)
    {
        $customer = collect($customer->toArray())->only(
            [
                'email',
                'socialName',
                'phone',
                'address',
                'assertedIncome',
                'pep',
                'occupation',
            ]
        )->toArray();

        return $this->patch('/customers/'.$documentNumber,
            ['data' => array_filter($customer)],
            $correlationId,
            true
        );
    }

    /**
     * Business offboarding
     *
     * @param  string|null  $reason  HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function cancelBusiness(
        string $documentNumber,
        ?string $reason = 'HOLDER_REQUEST',
        ?string $correlationId = null
    ): mixed {
        return $this->patch('/business/'.$documentNumber.'/cancel', [
            'reason' => $reason,
        ], $correlationId, true);
    }

    /**
     * Get customer
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getCustomer(string $documentNumber, string $resultLevel = 'DETAILED'): mixed
    {
        return $this->get("/customers/{$documentNumber}?resultLevel={$resultLevel}");
    }

    /**
     * Get customer
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getBusinessCustomer(string $documentNumber, string $resultLevel = 'DETAILED'): mixed
    {
        return $this->get("/business/{$documentNumber}?resultLevel={$resultLevel}");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getCustomerAccounts(string $documentNumber): mixed
    {
        return $this->get("/customers/{$documentNumber}/accounts");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getBusinessCustomerAccounts(string $documentNumber): mixed
    {
        return $this->get("/business/{$documentNumber}/accounts");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function createCustomerAccount(
        string $documentNumber, 
        PaymentAccount $paymentAccount, 
        ?string $idempotencyKey = null
    ): mixed
    {
        $this->setHeaders([
            'Idempotency-Key' => $idempotencyKey ?: Uuid::uuid4()->toString(),
        ]);

        return $this->post(
                "/customers/{$documentNumber}/accounts",
                $paymentAccount->toArray(),
                null,
                true
            );
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function createBusinessCustomerAccount(
        string $documentNumber,
        PaymentAccount $paymentAccount,
        ?string $idempotencyKey = null
    ): mixed
    {
        $this->setHeaders([
            'Idempotency-Key' => $idempotencyKey ?: Uuid::uuid4()->toString(),
        ]);

        return $this->post(
                "/business/{$documentNumber}/accounts",
                $paymentAccount->toArray(),
                null,
                true
            );
    }

    /**
     * Validate of boleto or dealership
     *
     * @param  string  $code  - Digitable line
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function paymentValidate(string $code, string $correlationId): mixed
    {
        return $this->post('/bill-payment/validate', ['code' => $code], $correlationId, true);
    }

    /**
     * Confirmation of payment of boleto or dealership
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function paymentConfirm(
        BillPayment $billPayment,
        string $correlationId
    ): mixed {
        return $this->post('/bill-payment/confirm', $billPayment->toArray(), $correlationId, true);
    }

    /**
     * Create a new PIX key link with account.
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function registerPixKey(PixEntries $pixEntries, ?string $hash = null): mixed
    {
        if ($hash) {
            $this->setHeaders(['x-bkly-transactional-hash' => $hash]);
        }

        return $this->post('/pix/entries', [
            'addressingKey' => $pixEntries->addressingKey->toArray(),
            'account' => $pixEntries->account->toArray(),
        ], null, true);
    }

    /**
     * Gets the list of address keys linked to an account.
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getPixAddressingKeys(string $accountNumber): mixed
    {
        return $this->get("/accounts/$accountNumber/addressing-keys");
    }

    /**
     * Gets details of the account linked to an addressing key.
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getPixAddressingKeyValue(string $documentNumber, string $addressinKeyValue): mixed
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);

        return $this->get("/pix/entries/$addressinKeyValue");
    }

    /**
     * Delete a key link with account.
     *
     * @return array|mixed
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function deletePixAddressingKeyValue(string $addressingKeyValue): mixed
    {
        return $this->delete("/pix/entries/$addressingKeyValue");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function pixCashout(PixCashoutInterface $pixCashout, string $correlationId): mixed
    {
        return $this->post('/pix/cash-out', $pixCashout->toArray(), $correlationId, true);
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function pixRefund(PixCashoutInterface $pixRefund): mixed
    {
        return $this->post('/pix/cash-out:refund', $pixRefund->toArray(), null, true);
    }

    /**
     * @throws RequestException
     * @throws RequestException
     */
    public function qrCode(string $documentNumber, PixStaticQrCode $data): array
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);

        return $this->post('/pix/qrcodes/static/transfer', $data->toArray(), null, true);
    }

    /**
     * @throws RequestException
     * @throws RequestException
     */
    public function dynamicQrCode(string $documentNumber, PixDynamicQrCode $data): array
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);

        return $this->post('/pix/qrcodes/dynamic/payment', $data->toArray(), null, true);
    }

    /**
     * @throws RequestException
     * @throws RequestException
     */
    public function qrCodeDecode(PixQrCodeData $data): array
    {
        $qrCode = $data->toArray();

        $this->setHeaders([
            'x-bkly-pix-user-id' => $qrCode['documentNumber'],
        ]);

        return $this->post('/pix/qrcodes/decode', $qrCode, null, true);
    }

    /**
     * Get webhooks processed messages
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function getWebhookMessages(
        string $startDate,
        string $endDate,
        ?string $state = null,
        ?string $eventName = null,
        ?string $context = null,
        int $page = 1,
        int $pagesize = 100
    ): array {
        $query = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'state' => $state,
            'eventName' => $eventName,
            'context' => $context,
            'page' => $page,
            'pageSize' => $pagesize,
        ];

        return $this->get(
            '/webhooks/processed-messages',
            $query
        );
    }

    /**
     * Reprocess webhook message
     *
     * @throws RequestException
     */
    public function reprocessWebhookMessage(string $idempotencyKey): ?array
    {
        return $this->post('/webhooks/processed-messages/'.$idempotencyKey, [], null, true);
    }

    /**
     * Get limits by feature
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function getFeatureLimits(string $documentNumber, string $limitType, string $featureName): array
    {
        return $this->get('/holders/'.$documentNumber.'/limits/'.$limitType.'/features/'.$featureName);
    }

    /**
     * Update customer limits by feature
     *
     * @param  array|mixed  $data
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function updateCustomerLimits(string $documentNumber, array $data): array
    {
        return $this->put('/holders/'.$documentNumber.'/max-limits', $data);
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    private function get(
        string $endpoint,
        array|string|null $query = null,
        mixed $correlation_id = null,
        bool $responseJson = true
    ): mixed {
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
     * @return array|mixed
     *
     * @throws RequestException
     */
    private function post(
        string $endpoint,
        ?array $body = null,
        ?string $correlation_id = null,
        bool $asJson = false
    ): mixed {
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

        return $request->post($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @param  array|null  $body
     * @param  string  $fieldName
     * @return array|mixed
     *
     * @throws RequestException
     */
    private function put(
        string $endpoint,
        array $body = [],
        ?string $correlation_id = null,
        bool $asJson = false,
        bool $attachment = false,
        ?DocumentAnalysis $document = null
    ): mixed {
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

        return $request->put($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @param  array|null  $body
     * @param  string  $fieldName
     * @return array|mixed
     *
     * @throws RequestException
     */
    private function postDocument(
        string $endpoint,
        array $body = [],
        ?string $correlation_id = null,
        bool $asJson = false,
        bool $attachment = false,
        ?DocumentAnalysis $document = null
    ): mixed {
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
     * @param  array|null  $body
     * @return array|mixed
     *
     * @throws RequestException
     */
    private function patch(
        string $endpoint,
        array $body = [],
        ?string $correlation_id = null,
        bool $asJson = false
    ): mixed {
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
     * @param  array|null  $body
     * @return array|mixed
     *
     * @throws RequestException
     */
    private function delete(string $endpoint, array $body = []): mixed
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
     * @param  string  $version  API version
     * @return $this
     */
    private function setApiVersion($version = '1.0'): static
    {
        $this->api_version = $version;

        return $this;
    }

    /**
     * Add cert options to request
     */
    private function setRequestMtls(PendingRequest $request): PendingRequest
    {
        return $request->withOptions([
            'cert' => $this->mtlsCert,
            'ssl_key' => [$this->mtlsKey, $this->mtlsPassphrase],
        ]);
    }

    /**
     * @param  array  $headers
     * @return array|string[]
     */
    private function getHeaders($headers = []): array
    {
        $default_headers = $this->headers;

        if (count($headers) > 0) {
            $default_headers = array_merge($headers, $default_headers);
        }

        return $default_headers;
    }

    /**
     * @param  array  $header
     */
    private function setHeaders($header): void
    {
        $this->headers = array_merge($this->headers, $header);
    }

    private function requireCorrelationId(string $endpoint): bool
    {
        $not_required_endpoints = [
            '/banklist',
            '/connect/token',
        ];

        return ! in_array($endpoint, $not_required_endpoints);
    }

    private function getFinalUrl(string $endpoint): string
    {
        return $this->api_url.$endpoint;
    }
}
