<?php

namespace WeDevBr\Bankly\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyInvoice;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Invoice\InstallmentAdvance;
use WeDevBr\Bankly\Types\Invoice\InstallmentSimulation;
use WeDevBr\Bankly\Types\Invoice\InvoicePayment;

class BanklyInvoiceTest extends TestCase
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
    public function test_get_open_invoice(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/document/47742663023/proxy/123456/open', [
            'statementId' => 1,
            'cycleType' => 'Open',
            'balance' => 1500.00,
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $response = $invoice->getOpenInvoice('47742663023', '123456');

        $this->assertEquals(1, $response['statementId']);
        $this->assertEquals('Open', $response['cycleType']);
    }

    #[Test]
    public function test_get_invoice_by_id(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/42', [
            'statementId' => 42,
            'cycleType' => 'Closed',
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $response = $invoice->getInvoiceById('42');

        $this->assertEquals(42, $response['statementId']);
    }

    #[Test]
    public function test_get_invoices_by_period(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/document/47742663023/proxy/123456*', [
            ['statementId' => 1],
            ['statementId' => 2],
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $response = $invoice->getInvoicesByPeriod('47742663023', '123456', Carbon::parse('2026-01-01'), Carbon::parse('2026-01-31'));

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_get_credit_limit(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/document/47742663023/proxy/123456/limits', [
            'limit' => 5000,
            'limitActive' => 3000,
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $response = $invoice->getCreditLimit('47742663023', '123456');

        $this->assertEquals(5000, $response['limit']);
    }

    #[Test]
    public function test_get_payment_options(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/42/payment-options', [
            'options' => ['full', 'minimum'],
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $response = $invoice->getPaymentOptions('42');

        $this->assertArrayHasKey('options', $response);
    }

    #[Test]
    public function test_generate_payment(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/42/payment', [
            'id' => 'payment123',
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $payment = new InvoicePayment;
        $payment->paymentType = 'Cash';
        $payment->amount = ['value' => 1500, 'currency' => 'BRL'];
        $response = $invoice->generatePayment('42', $payment);

        $this->assertEquals('payment123', $response['id']);
    }

    #[Test]
    public function test_simulate_installments(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/42/installments', [
            'installments' => [1, 2, 3, 4, 5, 6],
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $simulation = new InstallmentSimulation;
        $simulation->minTerm = 2;
        $simulation->maxTerm = 6;
        $response = $invoice->simulateInstallments('42', $simulation);

        $this->assertArrayHasKey('installments', $response);
    }

    #[Test]
    public function test_simulate_installment_advance(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/42/item/99/installment-advance', [
            'value' => 500,
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $response = $invoice->simulateInstallmentAdvance('42', '99');

        $this->assertEquals(500, $response['value']);
    }

    #[Test]
    public function test_confirm_installment_advance(): void
    {
        Http::fake($this->fakeHttp('/cards/invoices/42/item/99/advancement', [
            'id' => 'advance123',
        ]));
        $this->auth();

        $invoice = new BanklyInvoice;
        $advance = new InstallmentAdvance;
        $advance->installmentAdvanceQuantity = 2;
        $advance->removeInterest = true;
        $response = $invoice->confirmInstallmentAdvance('42', '99', $advance);

        $this->assertEquals('advance123', $response['id']);
    }
}
