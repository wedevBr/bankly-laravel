<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Support\Contracts\BusinessCustomerInterface;
use WeDevBr\Bankly\Support\Contracts\CorporationBusinessCustomerInterface;
use WeDevBr\Bankly\Support\Contracts\PixDynamicQrCodeInterface;
use WeDevBr\Bankly\Types\Customer\PaymentAccount;

class BanklyCoreTest extends TestCase
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
    public function test_get_bank_list(): void
    {
        Http::fake($this->fakeHttp('/banklist*', [
            ['bankCode' => '001', 'bankName' => 'Bank'],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getBankList();

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_get_balance(): void
    {
        Http::fake($this->fakeHttp('/account/balance*', [
            'amount' => 1000.00,
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getBalance('0001', '12345');

        $this->assertEquals(1000.00, $response['amount']);
    }

    #[Test]
    public function test_get_account(): void
    {
        Http::fake($this->fakeHttp('/accounts/12345*', [
            'account' => '12345',
            'branch' => '0001',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getAccount('12345');

        $this->assertEquals('12345', $response['account']);
    }

    #[Test]
    public function test_get_events(): void
    {
        Http::fake($this->fakeHttp('/events*', [
            'events' => [],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getEvents('0001', '12345');

        $this->assertArrayHasKey('events', $response);
    }

    #[Test]
    public function test_get_statement(): void
    {
        Http::fake($this->fakeHttp('/account/statement*', [
            'statements' => [],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getStatement('0001', '12345');

        $this->assertArrayHasKey('statements', $response);
    }

    #[Test]
    public function test_transfer(): void
    {
        Http::fake($this->fakeHttp('/fund-transfers', [
            'authenticationCode' => 'auth123',
        ], 202));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->transfer(
            100,
            'Test transfer',
            ['bankCode' => '001', 'branch' => '0001', 'account' => '12345'],
            ['bankCode' => '001', 'branch' => '0001', 'account' => '67890']
        );

        $this->assertEquals('auth123', $response['authenticationCode']);
    }

    #[Test]
    public function test_get_transfer_funds(): void
    {
        Http::fake($this->fakeHttp('/fund-transfers*', [
            'data' => [],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getTransferFunds('0001', '12345');

        $this->assertArrayHasKey('data', $response);
    }

    #[Test]
    public function test_find_transfer_fund_by_auth_code(): void
    {
        Http::fake($this->fakeHttp('/fund-transfers/auth123*', [
            'authenticationCode' => 'auth123',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->findTransferFundByAuthCode('0001', '12345', 'auth123');

        $this->assertEquals('auth123', $response['authenticationCode']);
    }

    #[Test]
    public function test_get_transfer_status(): void
    {
        Http::fake($this->fakeHttp('/fund-transfers/auth123/status*', [
            'status' => 'DONE',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getTransferStatus('0001', '12345', 'auth123');

        $this->assertEquals('DONE', $response['status']);
    }

    #[Test]
    public function test_close_account(): void
    {
        Http::fake($this->fakeHttp('/accounts/12345/closure', [], 204));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $bankly->closeAccount('12345');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_cancel_business(): void
    {
        Http::fake($this->fakeHttp('/business/47742663023/cancel', [], 204));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $bankly->cancelBusiness('47742663023');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_business_customer(): void
    {
        Http::fake($this->fakeHttp('/business/47742663023', [
            'document' => '47742663023',
        ]));
        $this->auth();

        $customer = new class implements BusinessCustomerInterface
        {
            public function toArray(): array
            {
                return ['businessName' => 'Test Biz'];
            }
        };

        $bankly = $this->getBanklyClient();
        $response = $bankly->businessCustomer('47742663023', $customer);

        $this->assertEquals('47742663023', $response['document']);
    }

    #[Test]
    public function test_corporation_business_customer(): void
    {
        Http::fake($this->fakeHttp('/corporation-business/47742663023', [
            'document' => '47742663023',
        ]));
        $this->auth();

        $customer = new class implements CorporationBusinessCustomerInterface
        {
            public function toArray(): array
            {
                return ['businessName' => 'Test Corp'];
            }
        };

        $bankly = $this->getBanklyClient();
        $response = $bankly->corporationBusinessCustomer('47742663023', $customer);

        $this->assertEquals('47742663023', $response['document']);
    }

    #[Test]
    public function test_create_business_customer_account(): void
    {
        Http::fake($this->fakeHttp('/business/47742663023/accounts', [
            'account' => '12345',
        ]));
        $this->auth();

        $account = new PaymentAccount;
        $account->accountType = 'PAYMENT_ACCOUNT';

        $bankly = $this->getBanklyClient();
        $response = $bankly->createBusinessCustomerAccount('47742663023', $account);

        $this->assertEquals('12345', $response['account']);
    }

    #[Test]
    public function test_get_business_customer(): void
    {
        Http::fake($this->fakeHttp('/business/47742663023*', [
            'document' => '47742663023',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getBusinessCustomer('47742663023');

        $this->assertEquals('47742663023', $response['document']);
    }

    #[Test]
    public function test_get_business_customer_accounts(): void
    {
        Http::fake($this->fakeHttp('/business/47742663023/accounts', [
            'accounts' => [],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getBusinessCustomerAccounts('47742663023');

        $this->assertArrayHasKey('accounts', $response);
    }

    #[Test]
    public function test_get_feature_limits(): void
    {
        Http::fake($this->fakeHttp('/holders/47742663023/limits/CASH_OUT/features/PIX', [
            'limit' => 5000,
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getFeatureLimits('47742663023', 'CASH_OUT', 'PIX');

        $this->assertEquals(5000, $response['limit']);
    }

    #[Test]
    public function test_update_customer_limits(): void
    {
        Http::fake($this->fakeHttp('/holders/47742663023/max-limits', []));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->updateCustomerLimits('47742663023', ['limits' => []]);

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_delete_pix_addressing_key_value(): void
    {
        Http::fake($this->fakeHttp('/pix/entries/key123', [], 204));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $bankly->deletePixAddressingKeyValue('key123');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_get_pix_addressing_key_value(): void
    {
        Http::fake($this->fakeHttp('/pix/entries/key123*', [
            'value' => 'key123',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getPixAddressingKeyValue('47742663023', 'key123');

        $this->assertEquals('key123', $response['value']);
    }

    #[Test]
    public function test_dynamic_qr_code(): void
    {
        Http::fake($this->fakeHttp('/pix/qrcodes/dynamic/payment', [
            'qrCode' => 'test',
        ]));
        $this->auth();

        $qrCode = new class implements PixDynamicQrCodeInterface
        {
            public function toArray(): array
            {
                return [
                    'recipientName' => 'Test',
                    'amount' => '100.00',
                    'conciliationId' => '12345678901234567890123456',
                ];
            }
        };

        $bankly = $this->getBanklyClient();
        $response = $bankly->dynamicQrCode('47742663023', $qrCode);

        $this->assertEquals('test', $response['qrCode']);
    }

    #[Test]
    public function test_pix_refund(): void
    {
        Http::fake($this->fakeHttp('/pix/cash-out:refund', [
            'authenticationCode' => 'refund123',
        ]));
        $this->auth();

        $refund = new class implements PixCashoutInterface
        {
            public function toArray(): array
            {
                return [
                    'amount' => '100',
                    'description' => 'Test refund',
                    'sender' => [
                        'documentNumber' => '47742663023',
                        'branch' => '0001',
                        'number' => '12345',
                    ],
                ];
            }

            public function validate(): void {}
        };

        $bankly = $this->getBanklyClient();
        $response = $bankly->pixRefund($refund);

        $this->assertEquals('refund123', $response['authenticationCode']);
    }

    #[Test]
    public function test_get_income_report(): void
    {
        Http::fake($this->fakeHttp('/accounts/12345/income-report*', [
            'income' => 50000,
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getIncomeReport('12345');

        $this->assertEquals(50000, $response['income']);
    }

    #[Test]
    public function test_get_income_report_print(): void
    {
        Http::fake($this->fakeHttp('/accounts/12345/income-report/print*', [
            'file' => 'base64data',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getIncomeReportPrint('12345');

        $this->assertEquals('base64data', $response['file']);
    }

    #[Test]
    public function test_get_webhook_messages(): void
    {
        Http::fake($this->fakeHttp('/webhooks/processed-messages*', [
            'messages' => [],
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->getWebhookMessages('2026-01-01', '2026-01-31');

        $this->assertArrayHasKey('messages', $response);
    }

    #[Test]
    public function test_reprocess_webhook_message(): void
    {
        Http::fake($this->fakeHttp('/webhooks/processed-messages/key123', [
            'id' => 'msg1',
        ]));
        $this->auth();

        $bankly = $this->getBanklyClient();
        $response = $bankly->reprocessWebhookMessage('key123');

        $this->assertEquals('msg1', $response['id']);
    }
}
