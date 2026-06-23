<?php

namespace WeDevBr\Bankly\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyWebhookBatchMessage;

class BanklyWebhookBatchMessageTest extends TestCase
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
    public function test_get_batch_messages(): void
    {
        Http::fake($this->fakeHttp('/webhooks/processed-messages/batch*', [
            'messages' => [],
        ]));
        $this->auth();

        $batchMessage = new BanklyWebhookBatchMessage;
        $response = $batchMessage->getBatchMessages(
            Carbon::parse('2026-01-01'),
            Carbon::parse('2026-01-31'),
            1,
            50
        );

        $this->assertArrayHasKey('messages', $response);
    }

    #[Test]
    public function test_reprocess_batch_message(): void
    {
        Http::fake($this->fakeHttp('/webhooks/processed-messages/batch', [
            'id' => 'batch1',
        ]));
        $this->auth();

        $batchMessage = new BanklyWebhookBatchMessage;
        $response = $batchMessage->reprocessBatchMessage(
            Carbon::parse('2026-01-01'),
            Carbon::parse('2026-01-31')
        );

        $this->assertEquals('batch1', $response['id']);
    }
}
