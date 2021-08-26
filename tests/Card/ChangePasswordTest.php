<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\Types\Card\Password;

/**
 * ChangePasswordTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class ChangePasswordTest extends TestCase
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
     * @return array
     */
    public function getFakerHttp(string $path, int $statusCode = 200)
    {
        return [
            config('bankly')['api_url'] . "{$path}" => Http::response([], $statusCode)
        ];
    }

    /**
     * @return Password
     */
    private function validPassword()
    {
        $passwordCard = new Password();
        $passwordCard->password = '1234';

        return $passwordCard;
    }

    /**
     * @return void
     */
    public function testSuccessChangePassword()
    {
        Http::fake($this->getFakerHttp("/cards/12345678/password", 200));

        $card = new BanklyCard();
        $card->changePassword('12345678', $this->validPassword());

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            return $body['password'] === '1234'
                && Str::contains($request->url(), '12345678/password');
        });
    }
}
