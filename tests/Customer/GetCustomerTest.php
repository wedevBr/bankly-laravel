<?php

namespace WeDevBr\Bankly\Tests\Customer;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Tests\TestCase;

/**
 * GetCustomerTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class GetCustomerTest extends TestCase
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
    public function testSuccessGetCustomerData()
    {
        Http::fake($this->getFakerHttp([
            'phone' => [
                'countryCode' => 'string',
                'number' => 'string',
            ],
            'address' => [
                'zipCode' => 'string',
                'addressLine' => 'string',
                'buildingNumber' => 'string',
                'complement' => 'string',
                'neighborhood' => 'string',
                'city' => 'string',
                'state' => 'string',
                'country' => 'string',
            ],
            'email' => 'string',
            'motherName' => 'string',
            'birthDate' => '2021-05-16T15:01:58.992Z',
            'isPoliticallyExposedPerson' => true,
            'reasons' => [
                'string',
            ],
            'documentNumber' => 'string',
            'registerName' => 'string',
            'socialName' => 'string',
            'status' => 'PENDING_APPROVAL',
            'profile' => 'COMPLETE',
            'createdAt' => '2021-05-16T15:01:58.992Z',
            'updatedAt' => '2021-05-16T15:01:58.992Z'
        ], 200));

        $client = $this->getBanklyClient();
        $response = $client->getCustomer('12345678909');

        Http::assertSent(function ($request) {
            return Str::contains($request->url(), 'customers/12345678909?resultLevel=DETAILED');
        });

        $this->assertEquals('PENDING_APPROVAL', $response['status']);
        $this->assertEquals('COMPLETE', $response['profile']);
    }
}
