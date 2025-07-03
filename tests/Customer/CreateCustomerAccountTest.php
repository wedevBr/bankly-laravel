<?php

namespace WeDevBr\Bankly\Tests\Customer;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Tests\TestCase;
use WeDevBr\Bankly\Types\Customer\PaymentAccount;

/**
 * CreateCustomerAccountTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CreateCustomerAccountTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path)
    {
        return [
            config('bankly')['api_url']."{$path}" => Http::response([
                'balance' => [
                    'inProcess' => [
                        'amount' => 0.0,
                        'currency' => 'BRL',
                    ],
                    'available' => [
                        'amount' => 0.0,
                        'currency' => 'BRL',
                    ],
                    'blocked' => [
                        'amount' => 0.0,
                        'currency' => 'BRL',
                    ],
                ],
                'status' => 'ACTIVE',
                'branch' => '0001',
                'number' => '215139',
            ], 201),
        ];
    }

    /**
     * @return void
     */
    public function test_success_create_customer_account()
    {
        Http::fake($this->getFakerHttp('/customers/12345678909/accounts'));

        $paymentAccount = new PaymentAccount;
        $paymentAccount->accountType = 'PAYMENT_ACCOUNT';

        $client = $this->getBanklyClient();
        $response = $client->createCustomerAccount('12345678909', $paymentAccount);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            return $body['accountType'] === 'PAYMENT_ACCOUNT';
        });

        $this->assertEquals($response['status'], 'ACTIVE');
        $this->assertEquals($response['branch'], '0001');
        $this->assertEquals($response['number'], '215139');
    }
}
