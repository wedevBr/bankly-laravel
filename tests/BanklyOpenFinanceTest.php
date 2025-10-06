<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyOpenFinance;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Inputs\Ticket;

class BanklyOpenFinanceTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    private function getFakerHttp(string $path, array $response, int $statusCode = 200): array
    {
        return [
            config('bankly')['api_url'].$path => Http::response($response, $statusCode),
        ];
    }

    private function validTicket(): Ticket
    {
        $ticket = new Ticket;
        $ticket->setRequestUri('https://example.com/callback')
            ->setClientId('client123')
            ->setDocumentNumber('12345678901');

        return $ticket;
    }

    public function test_success_create_ticket()
    {
        Http::fake($this->getFakerHttp('/openfinance/consent-flow/ticket', [
            'ticketId' => '12345',
            'status' => 'CREATED',
        ], 201));

        $client = new BanklyOpenFinance;
        $ticket = $this->validTicket();

        $response = $client->createTicket($ticket);

        Http::assertSent(function (Request $request) {
            $body = collect($request->data());

            return $body['requestUri'] === 'https://example.com/callback'
                && $body['clientId'] === 'client123'
                && $body['documentNumber'] === '12345678901'
                && $request->hasHeader('Idempotency-Key');
        });

        $this->assertEquals('CREATED', $response['status']);
    }

    public function test_success_create_ticket_with_custom_idempotency_key()
    {
        Http::fake($this->getFakerHttp('/openfinance/consent-flow/ticket', [
            'ticketId' => '12345',
            'status' => 'CREATED',
        ], 201));

        $client = new BanklyOpenFinance;
        $ticket = $this->validTicket();
        $customIdempotencyKey = 'custom-key-123';

        $response = $client->createTicket($ticket, $customIdempotencyKey);

        Http::assertSent(function (Request $request) use ($customIdempotencyKey) {
            return $request->header('Idempotency-Key')[0] === $customIdempotencyKey;
        });
    }

    public function test_success_create_consent_management()
    {
        Http::fake($this->getFakerHttp('/openfinance/consent-management/ticket', [
            'consentId' => '67890',
            'status' => 'CREATED',
        ], 201));

        $client = new BanklyOpenFinance;

        $response = $client->createConsentManagement('12345678', '12345678901', 1);

        Http::assertSent(function (Request $request) {
            $body = collect($request->data());

            return $body['accountNumber'] === '12345678'
                && $body['documentNumber'] === '12345678901'
                && $body['redirectType'] === 1
                && $request->hasHeader('Idempotency-Key');
        });

        $this->assertEquals('CREATED', $response['status']);
    }

    public function test_success_create_consent_management_with_custom_idempotency_key()
    {
        Http::fake($this->getFakerHttp('/openfinance/consent-management/ticket', [
            'consentId' => '67890',
            'status' => 'CREATED',
        ], 201));

        $client = new BanklyOpenFinance;
        $customIdempotencyKey = 'custom-consent-key-123';

        $response = $client->createConsentManagement('12345678', '12345678901', 2, $customIdempotencyKey);

        Http::assertSent(function (Request $request) use ($customIdempotencyKey) {
            $body = collect($request->data());

            return $body['redirectType'] === 2
                && $request->header('Idempotency-Key')[0] === $customIdempotencyKey;
        });
    }

    public function test_create_consent_management_with_invalid_redirect_type_throws_exception()
    {
        $client = new BanklyOpenFinance;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid redirect type');

        $client->createConsentManagement('12345678', '12345678901', 4);
    }

    public function test_create_consent_management_with_invalid_redirect_type_zero_throws_exception()
    {
        $client = new BanklyOpenFinance;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid redirect type');

        $client->createConsentManagement('12345678', '12345678901', 0);
    }
}
