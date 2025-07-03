<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Card\Wallet;

/**
 * DigitalWalletTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class DigitalWalletTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth();
    }

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path, int $statusCode = 200)
    {
        return [
            config('bankly')['api_url']."{$path}" => Http::response([
                [
                    'data' => 'mkzjfhcnnhat84y583hguim49801mkzjfhcnnhat84y583hguim49801mkzjfhcnnhat84y583hguim49801mkzjfhcnnhat84y5',
                    'lastFourDigits' => '1534',
                    'phoneNumber' => '11999999999',
                    'address' => [],
                ],
            ], $statusCode),
        ];
    }

    /**
     * @return Wallet
     */
    private function validWallet()
    {
        $wallet = new Wallet;
        $wallet->proxy = '12345678';
        $wallet->wallet = 'GooglePay';
        $wallet->brand = 'Mastercard';

        return $wallet;
    }

    /**
     * @return void
     */
    public function test_success_generate_digital_wallet()
    {
        Http::fake($this->getFakerHttp('/cards-pci/12345678/wallet/GooglePay/brand/Mastercard', 200));

        $card = new BanklyCard;
        $card->digitalWallet($this->validWallet());

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            return Str::contains($request->url(), '12345678/wallet/GooglePay/brand/Mastercard');
        });
    }

    /**
     * @return void
     */
    public function test_validate_proxy()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('proxy should be a string');
        $wallet = $this->validWallet();
        $wallet->proxy = null;
        $wallet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_empty_wallet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('wallet should be a string');
        $wallet = $this->validWallet();
        $wallet->wallet = null;
        $wallet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_invalid_wallet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this wallet is not valid');
        $wallet = $this->validWallet();
        $wallet->wallet = 'WhatsappPay';
        $wallet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_empty_brand()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('brand should be a string');
        $wallet = $this->validWallet();
        $wallet->brand = null;
        $wallet->validate();
    }

    /**
     * @return void
     */
    public function test_validate_invalid_brand()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this brand is not valid');
        $wallet = $this->validWallet();
        $wallet->brand = 'Diners';
        $wallet->validate();
    }
}
