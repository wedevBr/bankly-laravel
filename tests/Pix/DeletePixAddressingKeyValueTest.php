<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;

/**
 * DeletePixAddressingKeyValueTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class DeletePixAddressingKeyValueTest extends TestCase
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
            config('bankly')['api_url'].'/pix/entries/*' => Http::response($response, $statusCode),
        ];
    }

    /**
     * @return void
     */
    public function test_success_get_pix_addressing_keys()
    {
        Http::fake($this->getFakerHttp([], 204));

        $client = $this->getBanklyClient();
        $client->deletePixAddressingKeyValue('12345678909');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains($request->url(), 'pix/entries/12345678909');
        });
    }
}
