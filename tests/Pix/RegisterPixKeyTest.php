<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\Types\Pix\PixEntries;

/**
 * RegisterPixKeyTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class RegisterPixKeyTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return PixEntries
     */
    public function validPixEntries()
    {
        $addressingKey = new AddressingKey();
        $addressingKey->type = 'CPF';
        $addressingKey->value = '12345678909';

        $addressingAccount = new AddressingAccount();
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1234';
        $addressingAccount->type = 'CHECKING';

        $pixEntries = new PixEntries();
        $pixEntries->addressingKey = $addressingKey;
        $pixEntries->account = $addressingAccount;

        return $pixEntries;
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
            config('bankly')['api_url'] . '/pix/entries' => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessRegisterPixKey()
    {
        Http::fake($this->getFakerHttp([
            'addressingKey' => [],
            'account' => [],
            'status' => 'OWNED',
            'createdAt' => '2021-04-20T13:46:19.193Z',
            'ownedAt' => '2021-04-20T13:46:19.193Z',
        ], 201));

        $client = new Bankly();
        $response = $client->registerPixKey($this->validPixEntries());

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            $addressingKey = $body['addressingKey'];
            $account = $body['account'];

            return $addressingKey['type'] === 'CPF'
                && $addressingKey['value'] === '12345678909'
                && $account['branch'] === '0001'
                && $account['number'] === '1234'
                && $account['type'] === 'CHECKING';
        });

        $this->assertArrayHasKey('addressingKey', $response);
        $this->assertArrayHasKey('account', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('createdAt', $response);
        $this->assertArrayHasKey('ownedAt', $response);
    }
}
