<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyCard;

/**
 * AllowContactlessTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class AllowContactlessTest extends TestCase
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
     * @return void
     */
    public function testSuccessAllowContactless()
    {
        Http::fake($this->getFakerHttp("/cards/12345678/contactless?allowContactless=false", 200));

        $card = new BanklyCard();
        $card->allowContactless('12345678', false);

        Http::assertSent(function ($request) {
            return Str::contains(
                $request->url(),
                '12345678/contactless?allowContactless=false'
            );
        });
    }
}
