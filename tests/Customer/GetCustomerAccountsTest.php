<?php

namespace WeDevBr\Bankly\Tests\Customer;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Tests\TestCase;

/**
 * GetCustomerAccountsTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class GetCustomerAccountsTest extends TestCase
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
            config('bankly')['api_url'] . '/customers/*' => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessGetCustomerAccounts()
    {
        Http::fake($this->getFakerHttp([
            [
                'status' => 'ACTIVE',
                'branch' => 'string',
                'number' => 'string',
            ],
            [
                'status' => 'ACTIVE',
                'branch' => 'string',
                'number' => 'string',
            ]
        ], 200));

        $client = $this->getBanklyClient();
        $response = $client->getCustomerAccounts('12345678909');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains($request->url(), 'customers/12345678909');
        });

        $this->assertEquals([
            'status' => 'ACTIVE',
            'branch' => 'string',
            'number' => 'string',
        ], $response[0]);
        $this->assertEquals([
            'status' => 'ACTIVE',
            'branch' => 'string',
            'number' => 'string',
        ], $response[1]);
    }
}
