<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyCard;

/**
 * CardsByAccountTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CardsByAccountTest extends TestCase
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
    public function getFakerHttp(int $statusCode = 200)
    {
        return [
            config('bankly')['api_url'] . '/cards/account/1234567890' => Http::response([
                [
                    'created' => '2020-07-20T22:53:12',
                    'companyKey' => 'Acesso',
                    'documentNumber' => '00000000000',
                    'activateCode' => 'A9991B2E491D',
                    'bankAgency' => '0001',
                    'bankAccount' => '0001',
                    'lastFourDigits' => '4321',
                    'proxy' => '22999903520114195',
                    'name' => 'Jose da Silva',
                    'alias' => 'Meu Cartão',
                    'cardType' => 'Virtual',
                    'status' => 'InTransitLocked',
                    'physicalBinds' => [],
                    'virtualBind' => [],
                    'allowContactless' => true,
                    'address' => [],
                    'historyStatus' => [],
                    'activatedAt' => NULL,
                    'lastUpdatedAt' => '2020-07-20T22:55:12',
                    'isActivated' => false,
                    'isLocked' => true,
                    'isCanceled' => false,
                    'isBuilding' => false,
                    'isFirtual' => true,
                    'isPre' => true,
                    'isPos' => false,
                ]
            ], $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessGetCardsByAccount()
    {
        Http::fake($this->getFakerHttp(200));

        $card = new BanklyCard();
        $response = $card->getByAccount('1234567890');

        Http::assertSent(function ($request) {
            return Str::contains(
                $request->url(),
                'cards/account/1234567890'
            );
        });

        $this->assertArrayHasKey('activateCode', $response[0]);
        $this->assertArrayHasKey('proxy', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('alias', $response[0]);
        $this->assertEquals('Virtual', $response[0]['cardType']);
        $this->assertEquals('InTransitLocked', $response[0]['status']);
    }
}
