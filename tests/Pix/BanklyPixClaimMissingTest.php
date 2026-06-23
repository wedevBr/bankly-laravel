<?php

namespace WeDevBr\Bankly\Tests\Pix;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyPixClaim;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Enums\Pix\CancelReasonEnum;
use WeDevBr\Bankly\Support\Contracts\PixClaimInterface;
use WeDevBr\Bankly\Tests\TestCase;

class BanklyPixClaimMissingTest extends TestCase
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
    public function test_claim(): void
    {
        Http::fake($this->fakeHttp('/pix/claims', [
            'claimId' => 'claim123',
        ]));
        $this->auth();

        $claimer = new class implements PixClaimInterface
        {
            public function toArray(): array
            {
                return [
                    'type' => 'OWNERSHIP',
                    'claimer' => [
                        'branch' => '0001',
                        'number' => '12345',
                    ],
                ];
            }
        };

        $claim = new BanklyPixClaim;
        $response = $claim->claim($claimer, '47742663023');

        $this->assertEquals('claim123', $response['claimId']);
    }

    #[Test]
    public function test_read(): void
    {
        Http::fake($this->fakeHttp('/pix/claims*', [
            'claims' => [],
        ]));
        $this->auth();

        $claim = new BanklyPixClaim;
        $response = $claim->read('47742663023', 'CLAIMER');

        $this->assertArrayHasKey('claims', $response);
    }

    #[Test]
    public function test_acknowledge(): void
    {
        Http::fake($this->fakeHttp('/pix/stub/claim/acknowledge', []));
        $this->auth();

        $claim = new BanklyPixClaim;
        $response = $claim->acknowledge(['claimId' => 'claim123'], '47742663023');

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_confirm(): void
    {
        Http::fake($this->fakeHttp('/pix/claims/claim123/confirm', [], 204));
        $this->auth();

        $claim = new BanklyPixClaim;
        $claim->confirm('47742663023', 'claim123');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_complete(): void
    {
        Http::fake($this->fakeHttp('/pix/claims/claim123/complete', [], 204));
        $this->auth();

        $claim = new BanklyPixClaim;
        $claim->complete('47742663023', 'claim123');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_cancel(): void
    {
        Http::fake($this->fakeHttp('/pix/claims/claim123/cancel', [], 204));
        $this->auth();

        $claim = new BanklyPixClaim;
        $claim->cancel('47742663023', 'claim123', CancelReasonEnum::CLAIMER_REQUEST);
        $this->assertTrue(true);
    }
}
