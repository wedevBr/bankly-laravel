<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;

/**
 * PhysicalCardTrackingTest class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PhysicalCardTrackingTest extends TestCase
{
    use WithFaker;

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
            config('bankly')['api_url']."{$path}" => Http::response([
                'createdDate' => '2021-06-23T09:07:21-07:00',
                'externalTracking' => [
                    'code' => '123456',
                    'partner' => 'Fedex',
                ],
                'name' => 'Card name',
                'alias' => 'Card Alias',
                'estimatedDeliveryDate' => '2021-06-30T09:07:21-07:00',
                'address' => [
                    [
                        'zipCode' => '29155909',
                        'address' => 'Rua Olegário Maciel',
                        'number' => '333',
                        'neighborhood' => 'Centro',
                        'complement' => 'Complement',
                        'city' => 'Vila Velha',
                        'state' => 'ES',
                        'country' => 'BR',
                        'isActive' => true,
                    ],
                    [
                        'zipCode' => '91370-50',
                        'address' => 'Rua Alberto Cruz',
                        'number' => '547',
                        'neighborhood' => 'Centro',
                        'complement' => 'Complement',
                        'city' => 'Porto Alegre',
                        'state' => 'RS',
                        'country' => 'BR',
                        'isActive' => false,
                    ],
                ],
                'status' => [
                    [
                        'createdDate' => '2021-06-25T09:07:21-07:00',
                        'type' => 'type',
                        'reason' => 'reason',
                    ],
                    [
                        'createdDate' => '2021-06-24T09:07:21-07:00',
                        'type' => 'type',
                        'reason' => 'reason',
                    ],
                ],
                'finalized' => [
                    [
                        'createdDate' => '2021-06-29T09:07:21-07:00',
                        'recipientName' => 'Juliana Almeida',
                        'recipientKinship' => 'Mother',
                        'documentNumber' => 84569512357,
                        'attempts' => 1,
                    ],
                ],
            ], $statusCode),
        ];
    }

    /**
     * @return void
     */
    public function testSuccessNextStatus()
    {
        Http::fake($this->getFakerHttp('/cards/12345678/tracking', 200));

        $card = new BanklyCard;
        $response = $card->cardTracking('12345678');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains(
                $request->url(),
                '12345678/tracking'
            );
        });
        $this->assertArrayHasKey('createdDate', $response);
        $this->assertArrayHasKey('externalTracking', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('alias', $response);
        $this->assertArrayHasKey('estimatedDeliveryDate', $response);
        $this->assertArrayHasKey('address', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('finalized', $response);
    }
}
