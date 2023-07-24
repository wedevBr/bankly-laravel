<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Card\Activate;
use WeDevBr\Bankly\BanklyCard;

/**
 * ActivateCardTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class ActivateCardTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     */
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
     * @return Activate
     */
    private function validActivateCard($password = '1234', $activateCode = 'A0DDDC0951D1')
    {
        $activate = new Activate();
        $activate->activateCode = $activateCode;
        $activate->password = $password;

        return $activate;
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path, array $response, int $statusCode = 200)
    {
        return [
            config('bankly')['api_url'] . "{$path}" => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessActivateCard()
    {
        Http::fake($this->getFakerHttp("/cards/2370021007715002820/activate", [], 200));

        $card = new BanklyCard();
        $response = $card->activate('2370021007715002820', $this->validActivateCard());

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            return $body['activateCode'] === 'A0DDDC0951D1'
                && $body['password'] === '1234';
        });
    }

    /**
     * @return void
     */
    public function testValidatePassword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('password should be a numeric string');
        $duplicateCard = $this->validActivateCard();
        $duplicateCard->password = 'A123';
        $duplicateCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateActivateCode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid Activation code');
        $activate = $this->validActivateCard('1234', '123asd912');
        $activate->activateCode = '';
        $activate->validate();
    }
}
