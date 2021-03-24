<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BillPayment;

/**
 * PaymentTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PaymentTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
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
    public function testSuccessValidatePayment()
    {
        Http::fake($this->getFakerHttp("/bill-payment/validate", [
            'id' => '94c45428-65f1-4e96-a16c-748119e26a96'
        ]));

        $code = '34191790010104351004791020150008785680026000';
        $correlationId = '111222333444555';

        $client = new Bankly();
        $response = $client->paymentValidate($code, $correlationId);

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            $header = collect($request->header('x-correlation-id'))->first();

            return $body['code'] === '34191790010104351004791020150008785680026000'
                && $request->hasHeader('x-correlation-id', '111222333444555');
        });

        $this->assertArrayHasKey('id', $response);
    }

    /**
     * @return void
     */
    public function testSuccessConfirmPayment()
    {
        Http::fake($this->getFakerHttp("/bill-payment/confirm", [
            'authenticationCode' => '94c45428-65f1-4e96-a16c-748119e26a96'
        ]));

        $billPayment = new BillPayment();
        $billPayment->amount = 789.49;
        $billPayment->description = 'Mensalidade Escola';
        $billPayment->bankBranch = '0001';
        $billPayment->bankAccount = '1111';
        $billPayment->id = 'AAABBBCCCDDDEEE';
        $correlationId = '111222333444555';

        $client = new Bankly();
        $response = $client->paymentConfirm($billPayment, $correlationId);

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            return $body['amount'] === 789.49
                && $body['bankBranch'] === '0001'
                && $body['bankAccount'] === '1111'
                && $body['description'] === 'Mensalidade Escola'
                && $body['id'] === 'AAABBBCCCDDDEEE'
                && $request->hasHeader('x-correlation-id', '111222333444555');
        });

        $this->assertArrayHasKey('authenticationCode', $response);
    }
}
