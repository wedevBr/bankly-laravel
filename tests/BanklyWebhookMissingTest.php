<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyWebhook;
use WeDevBr\Bankly\Enums\Webhooks\WebhookEventNameEnum;
use WeDevBr\Bankly\Types\Webhooks\CreateWebhook;

class BanklyWebhookMissingTest extends TestCase
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

    private function validWebhook(): CreateWebhook
    {
        return new CreateWebhook(
            uri: 'https://example.com/webhook',
            privateKey: 'private-key-value',
            publicKey: 'public-key-value',
            name: 'Test Webhook',
            eventName: WebhookEventNameEnum::PIX_CASH_IN_WAS_RECEIVED,
            context: 'pix',
        );
    }

    #[Test]
    public function test_register_webhook(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations', [
            'id' => 'wh1',
        ]));
        $this->auth();

        $webhook = new BanklyWebhook;
        $response = $webhook->registerWebhook($this->validWebhook());

        $this->assertEquals('wh1', $response['id']);
    }

    #[Test]
    public function test_get_all_webhooks(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations*', [
            ['id' => 'wh1'],
        ]));
        $this->auth();

        $webhook = new BanklyWebhook;
        $response = $webhook->getAllWebhooks('ACTIVE');

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_get_webhook_by_id(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations/wh1', [
            'id' => 'wh1',
        ]));
        $this->auth();

        $webhook = new BanklyWebhook;
        $response = $webhook->getWebhookById('wh1');

        $this->assertEquals('wh1', $response['id']);
    }

    #[Test]
    public function test_update_webhook(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations/wh1', [], 204));
        $this->auth();

        $webhook = new BanklyWebhook;
        $webhook->updateWebhook($this->validWebhook(), 'wh1');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_delete_webhook_by_id(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations/wh1', [], 204));
        $this->auth();

        $webhook = new BanklyWebhook;
        $webhook->deleteWebhookById('wh1');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_disable_webhook_by_id(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations/wh1/disable', [], 204));
        $this->auth();

        $webhook = new BanklyWebhook;
        $webhook->disableWebhookById('wh1');
        $this->assertTrue(true);
    }

    #[Test]
    public function test_enable_webhook_by_id(): void
    {
        Http::fake($this->fakeHttp('/webhooks/configurations/wh1/enable', [], 204));
        $this->auth();

        $webhook = new BanklyWebhook;
        $webhook->enableWebhookById('wh1');
        $this->assertTrue(true);
    }
}
