<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyCard;

/**
 * NextStatusTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class NextStatusTest extends TestCase
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
            config('bankly')['api_url'] . "{$path}" => Http::response([
                [
                    "value" => "Active",
                    "isDefinitive" => false,
                ],
                [
                    "value" => "LostOrTheftCanceled",
                    "isDefinitive" => true,
                ],
                [
                    "value" => "CanceledByCustomer",
                    "isDefinitive" => true,
                ]
            ], $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessNextStatus()
    {
        Http::fake($this->getFakerHttp("/cards/12345678/nextStatus", 200));

        $card = new BanklyCard();
        $response = $card->nextStatus('12345678');

        Http::assertSent(function ($request) {
            return Str::contains(
                $request->url(),
                '12345678/nextStatus'
            );
        });

        $this->assertEquals('Active', $response[0]['value']);
        $this->assertEquals('LostOrTheftCanceled', $response[1]['value']);
        $this->assertEquals('CanceledByCustomer', $response[2]['value']);
    }
}
