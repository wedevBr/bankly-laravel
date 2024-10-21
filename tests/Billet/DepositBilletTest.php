<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Billet\Address;
use WeDevBr\Bankly\Types\Billet\BankAccount;
use WeDevBr\Bankly\Types\Billet\DepositBillet;
use WeDevBr\Bankly\Types\Billet\Payer;

/**
 * DepositBilletTest class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class DepositBilletTest extends TestCase
{
    use WithFaker;

    /**
     * @param  object  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return DepositBillet
     */
    public function validDepositBillet()
    {
        $bankAccount = new BankAccount;
        $bankAccount->branch = '0001';
        $bankAccount->number = '1234';

        $address = new Address;
        $address->addressLine = 'address';
        $address->city = 'city';
        $address->state = 'state';
        $address->neighborhood = 'neighborhood';
        $address->zipCode = '36555000';

        $payer = new Payer;
        $payer->document = '12345678909';
        $payer->name = 'Jhon Smith';
        $payer->tradeName = 'Jhon Smith';
        $payer->address = $address;

        $depositBillet = new DepositBillet;
        $depositBillet->account = $bankAccount;
        $depositBillet->payer = $payer;
        $depositBillet->alias = 'Deposit Billet';
        $depositBillet->documentNumber = '12345678909';
        $depositBillet->amount = '69.99';
        $depositBillet->dueDate = now()->addDay()->format('Y-m-d');
        $depositBillet->type = 'Deposit';

        return $depositBillet;
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path, array $response, int $statusCode)
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600,
            ], 200),
            config('bankly')['api_url']."{$path}" => Http::response($response, $statusCode),
        ];
    }

    /**
     * @return void
     */
    public function testSuccessDepositBillet()
    {
        Http::fake($this->getFakerHttp('/bankslip', [
            'account' => [
                'number' => 'string',
                'branch' => 'string',
            ],
            'authenticationCode' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
        ], 202));

        $client = $this->getBilletClient();
        $response = $client->depositBillet($this->validDepositBillet());

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = $request->data();
            $account = $body['account'];
            $payer = $body['payer'];
            $address = $body['payer']['address'];

            return $account['branch'] === '0001'
                && $account['number'] === '1234'
                && $payer['document'] = '12345678909'
                && $payer['name'] === 'Jhon Smith'
                && $payer['tradeName'] === 'Jhon Smith'
                && $address['addressLine'] === 'address'
                && $address['city'] === 'city'
                && $address['state'] === 'state'
                && $address['zipCode'] === '36555000'
                && $body['alias'] === 'Deposit Billet'
                && $body['documentNumber'] === '12345678909'
                && $body['amount'] === '69.99'
                && $body['dueDate'] === now()->addDay()->format('Y-m-d')
                && $body['type'] === 'Deposit';
        });

        $this->assertArrayHasKey('account', $response);
        $this->assertArrayHasKey('authenticationCode', $response);
    }

    /**
     * @return void
     */
    public function testSuccessGetDepositBillet()
    {
        Http::fake($this->getFakerHttp('/bankslip/*', [
            'authenticationCode' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
            'updatedAt' => '2021-05-05T14:01:06.712Z',
            'ourNumber' => 'string',
            'digitable' => 'string',
            'status' => 'Accepted',
            'account' => [],
            'document' => 'string',
            'amount' => [],
            'dueDate' => '2021-05-05T14:01:06.713Z',
            'emissionDate' => '2021-05-05T14:01:06.713Z',
            'type' => 'Deposit',
            'payer' => [],
            'recipientFinal' => [],
            'recipientOrigin' => [],
            'payments' => [],
        ], 202));

        $client = $this->getBilletClient();
        $response = $client->getBillet('0001', '1234', '123456789123456789');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains($request->url(), '/branch/0001/number/1234/123456789123456789');
        });

        $this->assertArrayHasKey('authenticationCode', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('digitable', $response);
        $this->assertArrayHasKey('account', $response);
        $this->assertArrayHasKey('document', $response);
        $this->assertArrayHasKey('amount', $response);
        $this->assertArrayHasKey('dueDate', $response);
        $this->assertArrayHasKey('type', $response);
        $this->assertArrayHasKey('payer', $response);
    }

    /**
     * @return void
     */
    public function testSuccessGetDepositBilletByBarcode()
    {
        Http::fake($this->getFakerHttp('/bankslip/*', [
            'authenticationCode' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
            'updatedAt' => '2021-05-05T14:01:06.712Z',
            'ourNumber' => 'string',
            'digitable' => 'string',
            'status' => 'Accepted',
            'account' => [],
            'document' => 'string',
            'amount' => [],
            'dueDate' => '2021-05-05T14:01:06.713Z',
            'emissionDate' => '2021-05-05T14:01:06.713Z',
            'type' => 'Deposit',
            'payer' => [],
            'recipientFinal' => [],
            'recipientOrigin' => [],
            'payments' => [],
        ], 202));

        $client = $this->getBilletClient();
        $response = $client->getBilletByBarcode('123456789123456789123456789123456789');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains($request->url(), '/123456789123456789123456789123456789');
        });

        $this->assertArrayHasKey('authenticationCode', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('digitable', $response);
        $this->assertArrayHasKey('account', $response);
        $this->assertArrayHasKey('document', $response);
        $this->assertArrayHasKey('amount', $response);
        $this->assertArrayHasKey('dueDate', $response);
        $this->assertArrayHasKey('type', $response);
        $this->assertArrayHasKey('payer', $response);
    }

    /**
     * @return void
     */
    public function testSuccessGetDepositBilletByDate()
    {
        Http::fake($this->getFakerHttp('/bankslip/*', [
            'nextPageToken' => 'string',
            'data' => [
                'authenticationCode' => '3fa85f64-5717-4562-b3fc-2c963f66afa6',
                'alias' => 'string',
                'digitable' => 'string',
                'status' => 'Accepted',
                'amount' => [],
                'dueDate' => '2021-05-05T14:01:06.713Z',
                'emissionDate' => '2021-05-05T14:01:06.713Z',
                'barcode' => 'string',
                'payer' => [],
                'recipientFinal' => [],
                'recipientOrigin' => [],
                'payments' => [],
            ],
        ], 202));

        $datetime = now()->toDateTimeString();
        $client = $this->getBilletClient();
        $response = $client->getBilletByDate($datetime);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($datetime) {
            return Str::contains(urldecode($request->url()), "/searchstatus/{$datetime}");
        });

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('authenticationCode', $response['data']);
        $this->assertArrayHasKey('status', $response['data']);
        $this->assertArrayHasKey('digitable', $response['data']);
        $this->assertArrayHasKey('barcode', $response['data']);
        $this->assertArrayHasKey('amount', $response['data']);
        $this->assertArrayHasKey('dueDate', $response['data']);
        $this->assertArrayHasKey('payer', $response['data']);
    }

    /**
     * @return void
     */
    public function testSuccessPrintDepositBillet()
    {
        Http::fake($this->getFakerHttp('/bankslip/*', [], 200));

        $client = $this->getBilletClient();
        $client->printBillet('123456789123456789');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            return Str::contains($request->url(), '/123456789123456789/pdf');
        });
    }
}
