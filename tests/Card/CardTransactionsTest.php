<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyCard;

/**
 * CardTransactionsTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CardTransactionsTest extends TestCase
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
                'companyKey' => 'SDB_WEDEV',
                'nextPage' => 'mkzjfhcnnhat84y583hguim49801',
                'hasLastPage' => false,
                'transactions' => [
                    [
                        'account' => [
                            'number' => '00001',
                            'agency' => '0001',
                        ],
                        'amount' => [
                            'value' => 51.25,
                            'local' => 51.25,
                            'net' => 51.25,
                            'iof' => 0.0,
                            'markup' => 0.0,
                        ],
                        'merchant' => [
                            'id' => '207001540000011',
                            'name' => 'EC*MAGALU',
                            'mcc' => '2233',
                            'city' => 'SAO PAULO',
                        ],
                        'authorizationCode' => '980377',
                        'countryCode' => 'BR',
                        'currencyCode' => '986',
                        'entryMode' => 'HybridTerminalFailedConnection',
                        'status' => 'TransactionHoldWasApproved',
                        'transactionTimestamp' => '2021-06-06T16:48:11.1705083+00:00',
                        'transactionType' => 'Unknown',
                    ],
                ],
            ], $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessGetCardTransactions()
    {
        Http::fake($this->getFakerHttp("/cards/1234567890/*", 200));

        $card = new BanklyCard();
        $response = $card->transactions(
            '1234567890',
            1,
            20,
            '2021-06-06',
            '2021-06-07'
        );

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains(
                $request->url(),
                '1234567890/transactions?page=1&pageSize=20&startDate=2021-06-06&endDate=2021-06-07'
            );
        });

        $this->assertArrayHasKey('companyKey', $response);
        $this->assertArrayHasKey('nextPage', $response);
        $this->assertArrayHasKey('hasLastPage', $response);
        $this->assertArrayHasKey('transactions', $response);
        $this->assertEquals('TransactionHoldWasApproved', $response['transactions'][0]['status']);
    }

    /**
     * @return void
     */
    public function testReturnBadRequest()
    {
        Http::fake($this->getFakerHttp("/cards/1234567890/*", 400));

        $this->expectException(RequestException::class);

        $card = new BanklyCard();
        $card->transactions(
            '1234567890',
            1,
            20,
            '2021-06-06',
            '2021-06-07'
        );
    }
}
