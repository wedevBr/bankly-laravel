<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Card\Password;

/**
 * CardTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CardSecurityDataTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
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
                'cardNumber' => '000000000000000',
                'cvv' => '000',
                'expirationDate' => '05/09',
            ], $statusCode),
        ];
    }

    /**
     * @return Password
     */
    private function invalidPassword()
    {
        $passwordCard = new Password;
        $passwordCard->password = '123a';

        return $passwordCard;
    }

    /**
     * @return Password
     */
    private function validPassword()
    {
        $passwordCard = new Password;
        $passwordCard->password = '1234';

        return $passwordCard;
    }

    /**
     * @return void
     */
    public function testSuccessGetCardPciData()
    {
        $client = $this->getBanklyClient();

        Http::fake($this->getFakerHttp('/cards/2370021007715002820/pci', 200));

        $card = new BanklyCard;
        $response = $card->pciData('2370021007715002820', $this->validPassword());

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            return $body['password'] === '1234';
        });

        $this->assertArrayHasKey('cardNumber', $response);
        $this->assertArrayHasKey('cvv', $response);
        $this->assertArrayHasKey('expirationDate', $response);
    }

    /**
     * @return void
     */
    public function testValidatePassword()
    {
        $this->expectExceptionMessage('password should be a numeric string');
        $password = $this->invalidPassword();
        $password->validate();
    }
}
