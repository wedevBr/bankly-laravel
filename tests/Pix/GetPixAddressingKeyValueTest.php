<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;

/**
 * GetPixAddressingKeyValueTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class GetPixAddressingKeyValueTest extends TestCase
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
            config('bankly')['api_url'] . '/pix/entries/*' => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessGetPixAddressingKeys()
    {
        Http::fake($this->getFakerHttp([
            'endToEndId' => $this->faker->uuid,
            'addressingKey' => [],
            'account' => [],
            'status' => 'OWNED',
            'createdAt' => '2021-04-20T13:46:19.326Z',
            'ownedAt' => '2021-04-20T13:46:19.326Z',
        ], 200));

        $client = $this->getBanklyClient();
        $response = $client->getPixAddressingKeyValue('11111111111', '12345678909');

        Http::assertSent(function ($request) {
            return Str::contains($request->url(), 'pix/entries/12345678909')
                && $request->header('x-bkly-pix-user-id')[0] === '11111111111';
        });

        $this->assertArrayHasKey('endToEndId', $response);
        $this->assertArrayHasKey('addressingKey', $response);
        $this->assertArrayHasKey('account', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('createdAt', $response);
        $this->assertArrayHasKey('ownedAt', $response);
    }
}
