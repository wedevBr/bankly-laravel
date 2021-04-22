<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;

/**
 * GetPixAddressingKeysTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class GetPixAddressingKeysTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return array
     */
    public function getFakerHttp(array $response, int $statusCode)
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600
            ], 200),
            config('bankly')['api_url'] . '/accounts/*' => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessGetPixAddressingKeys()
    {
        Http::fake($this->getFakerHttp([
            [
                'type' => 'CPF',
                'value' => '12345678909',
            ]
        ], 200));

        $client = new Bankly();
        $response = $client->getPixAddressingKeys('1234');

        Http::assertSent(function ($request) {
            $body = collect($request->data());
            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            return Str::contains($request->url(), 'accounts/1234/addressing-keys');
        });

        $this->assertEquals([
            [
                'type' => 'CPF',
                'value' => '12345678909'
            ],
        ], $response);
    }
}
