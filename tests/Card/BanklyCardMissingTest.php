<?php

namespace WeDevBr\Bankly\Tests\Card;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Tests\TestCase;
use WeDevBr\Bankly\Types\Card\Wallet;

class BanklyCardMissingTest extends TestCase
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
    public function test_card_tracking(): void
    {
        Http::fake($this->fakeHttp('/cards/123456/tracking', [
            'trackingCode' => 'TC123',
        ]));
        $this->auth();

        $card = new BanklyCard;
        $response = $card->cardTracking('123456');

        $this->assertEquals('TC123', $response['trackingCode']);
    }

    #[Test]
    public function test_generate_activation_data(): void
    {
        Http::fake($this->fakeHttp('/cards-pci/12345678/wallet/ApplePay/brand/Mastercard/activation-data', [
            'activationData' => 'data',
        ]));
        $this->auth();

        $wallet = new Wallet;
        $wallet->proxy = '12345678';
        $wallet->wallet = 'ApplePay';
        $wallet->brand = 'Mastercard';

        $card = new BanklyCard;
        $response = $card->generateActivationData($wallet);

        $this->assertEquals('data', $response['activationData']);
    }
}
