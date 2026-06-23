<?php

namespace WeDevBr\Bankly\Tests\Billet;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyBillet;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Support\Contracts\BilletBankAccountInterface;
use WeDevBr\Bankly\Tests\TestCase;
use WeDevBr\Bankly\Types\Billet\BankAccount;
use WeDevBr\Bankly\Types\Billet\CancelBillet;

class BanklyBilletMissingTest extends TestCase
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
    public function test_cancel_billet(): void
    {
        Http::fake($this->fakeHttp('/bankslip/cancel', [], 204));
        $this->auth();

        $bankAccount = new BankAccount;
        $bankAccount->branch = '0001';
        $bankAccount->number = '12345';

        $cancelBillet = new CancelBillet;
        $cancelBillet->authenticationCode = 'auth123';
        $cancelBillet->account = $bankAccount;

        $billet = new BanklyBillet;
        $billet->cancelBillet($cancelBillet);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_bill_settlement_simulate(): void
    {
        Http::fake($this->fakeHttp('/bankslip/settlementpayment', [
            'settlement' => 100.00,
        ]));
        $this->auth();

        $bankAccount = new class implements BilletBankAccountInterface
        {
            public string $account = '12345';

            public string $branch = '0001';

            public function toArray(): array
            {
                return ['number' => $this->account, 'branch' => $this->branch];
            }
        };

        $billet = new BanklyBillet;
        $response = $billet->billSettlementSimulate($bankAccount, 'txid123');

        $this->assertEquals(100.00, $response['settlement']);
    }
}
