<?php

namespace WeDevBr\Bankly\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyAutomaticPix;
use WeDevBr\Bankly\BanklyScheduledPix;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\AutomaticPixAuthorizationEdit;
use WeDevBr\Bankly\Types\Pix\ScheduledPixPayment;
use WeDevBr\Bankly\Types\Pix\ScheduledPixRecurrence;

class BanklyNewEndpointsTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [BanklyServiceProvider::class];
    }

    private function fakeHttp(string $path, array $response, int $status = 200): array
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600,
            ], 200),
            config('bankly')['api_url'].$path => Http::response($response, $status),
        ];
    }

    #[Test]
    public function test_create_scheduled_pix(): void
    {
        Http::fake($this->fakeHttp('/pix/scheduling-payments', [
            'requestIdentifier' => 'sched123',
        ]));
        $this->auth();

        $scheduledPix = new BanklyScheduledPix;
        $payment = new ScheduledPixPayment;
        $payment->initiationForm = 'DICT';
        $payment->interbankSettlementAmount = 100.0;
        $payment->requestDateTime = '2026-06-23T10:00:00Z';
        $payment->dateSchedule = '2026-06-24';
        $payment->endToEndId = 'E12345678901234567890123456789012';
        $payment->debtor = [
            'accountIdentification' => '12345',
            'accountIssuer' => '0001',
            'accountType' => 'CACC',
            'name' => 'Test',
            'privateIdentification' => '01234567890',
        ];
        $payment->remittanceInformation = 'Test payment';
        $response = $scheduledPix->createScheduledPix('12345678909', $payment);

        $this->assertEquals('sched123', $response['requestIdentifier']);
    }

    #[Test]
    public function test_create_recurrence(): void
    {
        Http::fake($this->fakeHttp('/pix/scheduling-payments/recurrences', [
            'requestIdentifier' => 'rec123',
        ]));
        $this->auth();

        $scheduledPix = new BanklyScheduledPix;
        $recurrence = new ScheduledPixRecurrence;
        $recurrence->initiationForm = 'DICT';
        $recurrence->interbankSettlementAmount = 100.0;
        $recurrence->requestDateTime = '2026-06-23T10:00:00Z';
        $recurrence->dateSchedule = '2026-06-24';
        $recurrence->endToEndId = 'E12345678901234567890123456789012';
        $recurrence->debtor = [
            'accountIdentification' => '12345',
            'accountIssuer' => '0001',
            'accountType' => 'CACC',
            'name' => 'Test',
            'privateIdentification' => '01234567890',
        ];
        $recurrence->remittanceInformation = 'Test payment';
        $recurrence->frequency = 'Monthly';
        $recurrence->numberOfPayments = 12;
        $response = $scheduledPix->createRecurrence('12345678909', $recurrence);

        $this->assertEquals('rec123', $response['requestIdentifier']);
    }

    #[Test]
    public function test_get_recurrences(): void
    {
        Http::fake($this->fakeHttp('/pix/scheduling-payments/recurrences*', [
            ['requestIdentifier' => 'rec1'],
            ['requestIdentifier' => 'rec2'],
        ]));
        $this->auth();

        $scheduledPix = new BanklyScheduledPix;
        $response = $scheduledPix->getRecurrences('12345678909');

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_get_recurrence_by_id(): void
    {
        Http::fake($this->fakeHttp('/pix/scheduling-payments/recurrences/rec123', [
            'requestIdentifier' => 'rec123',
        ]));
        $this->auth();

        $scheduledPix = new BanklyScheduledPix;
        $response = $scheduledPix->getRecurrenceById('rec123', '12345678909');

        $this->assertEquals('rec123', $response['requestIdentifier']);
    }

    #[Test]
    public function test_cancel_recurrence(): void
    {
        Http::fake($this->fakeHttp('/pix/scheduling-payments/recurrences/rec123', [], 204));
        $this->auth();

        $scheduledPix = new BanklyScheduledPix;
        $scheduledPix->cancelRecurrence('rec123', '12345678909');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_edit_automatic_pix_authorization(): void
    {
        Http::fake($this->fakeHttp('/pix/automatic/authorization', [
            'idRecurrence' => 'rec123',
        ]));
        $this->auth();

        $automaticPix = new BanklyAutomaticPix;
        $authorization = new AutomaticPixAuthorizationEdit;
        $authorization->idRecurrence = 'rec123';
        $authorization->maximumValue = '500.00';
        $response = $automaticPix->editAuthorization('12345678909', $authorization);

        $this->assertEquals('rec123', $response['idRecurrence']);
    }

    #[Test]
    public function test_get_pix_cashout_status(): void
    {
        Http::fake($this->fakeHttp('/pix/cash-out/accounts/12345/authenticationcode/auth123*', [
            'status' => 'DONE',
            'authenticationCode' => 'auth123',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getPixCashoutStatus('12345', 'auth123');

        $this->assertEquals('DONE', $response['status']);
    }

    #[Test]
    public function test_get_bill_payments(): void
    {
        Http::fake($this->fakeHttp('/bill-payment*', [
            'data' => [['authenticationCode' => 'auth1']],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getBillPayments('0001', '12345');

        $this->assertArrayHasKey('data', $response);
    }

    #[Test]
    public function test_get_bill_payment_by_auth_code(): void
    {
        Http::fake($this->fakeHttp('/bill-payment/detail*', [
            'authenticationCode' => 'auth123',
            'amount' => 150,
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getBillPaymentByAuthCode('auth123');

        $this->assertEquals('auth123', $response['authenticationCode']);
    }

    #[Test]
    public function test_get_address_by_zip_code(): void
    {
        Http::fake($this->fakeHttp('/addresses/05402100', [
            'zipCode' => '05402100',
            'address' => 'Rua 6 de Março',
            'city' => 'Santarém',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getAddressByZipCode('05402100');

        $this->assertEquals('05402100', $response['zipCode']);
    }

    #[Test]
    public function test_get_dollar_rate_by_date(): void
    {
        Http::fake($this->fakeHttp('/network-authorization/fees/dolar-rates*', [
            'date' => '2026-06-23',
            'rate' => 5.25,
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getDollarRateByDate(Carbon::parse('2026-06-23'));

        $this->assertEquals(5.25, $response['rate']);
    }

    #[Test]
    public function test_get_dollar_rate_by_date_range(): void
    {
        Http::fake($this->fakeHttp('/network-authorization/fees/dolar-rates/date-range*', [
            ['date' => '2026-06-01', 'rate' => 5.20],
            ['date' => '2026-06-02', 'rate' => 5.25],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getDollarRateByDateRange(Carbon::parse('2026-06-01'), Carbon::parse('2026-06-02'));

        $this->assertIsArray($response);
    }
}
