<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Card\Address;
use WeDevBr\Bankly\Types\Card\Card;

/**
 * CardTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CardTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return Address
     */
    private function validAddress()
    {
        $address = new Address();
        $address->zipCode = '29155909';
        $address->address = 'Rua Olegário Maciel';
        $address->number = '333';
        $address->complement = 'Complement';
        $address->neighborhood = 'Centro';
        $address->city = 'Vila Velha';
        $address->state = 'ES';
        $address->country = 'BR';

        return $address;
    }

    /**
     * @return Card
     */
    private function validCard()
    {
        $card = new Card();
        $card->documentNumber = '01234567890';
        $card->cardName = 'Carla Dias';
        $card->alias = 'Carlinha';
        $card->bankAgency = '0001';
        $card->bankAccount = '11223344';
        $card->programId = '11223344112233441122334411223344';
        $card->password = '1234';
        $card->address = $this->validAddress();

        return $card;
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path, array $response, int $statusCode = 200)
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600
            ], 200),
            config('bankly')['api_url'] . "{$path}" => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessCreateVirtualCard()
    {
        $client = new Bankly();

        Http::fake($this->getFakerHttp("/cards/virtual", [
            'proxy' => '2370021007715002820',
            'activateCode' => 'A0DDDC0951D1',
        ], 202));

        $response = $client->virtualCard($this->validCard());

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            $address = $body['address'];

            return $body['documentNumber'] === '01234567890'
                && $body['cardName'] === 'Carla Dias'
                && $body['alias'] === 'Carlinha'
                && $body['bankAgency'] === '0001'
                && $body['bankAccount'] === '11223344'
                && $body['programId'] === '11223344112233441122334411223344'
                && $body['password'] === '1234'
                && $address['zipCode'] === '29155909'
                && $address['address'] === 'Rua Olegário Maciel'
                && $address['number'] === '100'
                && $address['complement'] === 'Complement'
                && $address['state'] === 'ES'
                && $address['city'] === 'Vila Velha'
                && $address['neighborhood'] === 'Centro'
                && $address['complement'] === 'APT 222'
                && $address['country'] === 'BR';
        });

        $this->assertArrayHasKey('proxy', $response);
        $this->assertArrayHasKey('activateCode', $response);
    }

    /**
     * @return void
     */
    public function testSuccessCreatePhisicalCard()
    {
        $client = new Bankly();

        Http::fake($this->getFakerHttp("/cards/phisical", [
            'proxy' => '2370021007715002820',
            'activateCode' => 'A0DDDC0951D1',
        ], 202));

        $response = $client->phisicalCard($this->validCard());

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            $address = $body['address'];

            return $body['documentNumber'] === '01234567890'
                && $body['cardName'] === 'Carla Dias'
                && $body['alias'] === 'Carlinha'
                && $body['bankAgency'] === '0001'
                && $body['bankAccount'] === '11223344'
                && $body['programId'] === '11223344112233441122334411223344'
                && $body['password'] === '1234'
                && $address['zipCode'] === '29155909'
                && $address['address'] === 'Rua Olegário Maciel'
                && $address['number'] === '100'
                && $address['complement'] === 'Complement'
                && $address['state'] === 'ES'
                && $address['city'] === 'Vila Velha'
                && $address['neighborhood'] === 'Centro'
                && $address['complement'] === 'APT 222'
                && $address['country'] === 'BR';
        });

        $this->assertArrayHasKey('proxy', $response);
        $this->assertArrayHasKey('activateCode', $response);
    }
}
