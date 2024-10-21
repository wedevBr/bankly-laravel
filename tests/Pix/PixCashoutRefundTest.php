<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutRefund;

/**
 * PixCashoutRefundTest class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PixCashoutRefundTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return AddressingAccount
     */
    public function validAccount()
    {
        $addressingAccount = new AddressingAccount;
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1111';
        $addressingAccount->type = 'CHECKING';

        return $addressingAccount;
    }

    /**
     * @return PixCashoutRefund
     */
    public function validPixCashoutRefund()
    {
        $pixCashoutRefund = new PixCashoutRefund;
        $pixCashoutRefund->account = $this->validAccount();
        $pixCashoutRefund->authenticationCode = '79bb6e53-6869-42c6-be15-2dba237f306b';
        $pixCashoutRefund->amount = '83.23';
        $pixCashoutRefund->refundCode = 'SL02';
        $pixCashoutRefund->description = 'Devolução de Pix recebido';

        return $pixCashoutRefund;
    }

    /**
     * @return array
     */
    public function getFakerHttp(array $response, int $statusCode) {}

    public function successResponse()
    {
        $response = [
            'authenticationCode' => 'string',
            'amount' => 99.98,
            'description' => 'string',
            'correlationId' => 'string',
            'sender' => [
                'account' => [
                    'branch' => 'string',
                    'number' => 'string',
                    'type' => 'CHECKING',
                ],
                'bank' => [
                    'ispb' => 'string',
                    'compe' => 'string',
                    'name' => 'string',
                ],
                'documentType' => 'string',
                'documentNumber' => 'string',
                'name' => 'string',
            ],
            'recipient' => [
                'account' => [
                    'branch' => 'string',
                    'number' => 'string',
                    'type' => 'CHECKING',
                ],
                'bank' => [
                    'ispb' => 'string',
                    'compe' => 'string',
                    'name' => 'string',
                ],
                'documentType' => 'string',
                'documentNumber' => 'string',
                'name' => 'string',
            ],
            'status' => 'CREATED',
            'createdAt' => '2021-10-26T13:40:51.2434384Z',
            'updatedAt' => '2021-10-26T13:40:51.4185601Z',
        ];

        Http::fake([
            config('bankly')['api_url'].'/pix/cash-out:refund' => Http::response($response, 202),
        ]);
    }

    /**
     * @return void
     */
    public function testSuccessPixCashout()
    {
        $this->successResponse();

        $client = $this->getBanklyClient();
        $response = $client->pixRefund($this->validPixCashoutRefund());

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            $account = $body['account'];

            return $body['amount'] === '83.23'
                && $body['authenticationCode'] === '79bb6e53-6869-42c6-be15-2dba237f306b'
                && $body['description'] === 'Devolução de Pix recebido'
                && $body['refundCode'] === 'SL02'
                && $account['branch'] === '0001'
                && $account['number'] === '1111'
                && $account['type'] === 'CHECKING';
        });

        $this->assertArrayHasKey('amount', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('sender', $response);
        $this->assertArrayHasKey('recipient', $response);
        $this->assertArrayHasKey('authenticationCode', $response);
        $this->assertArrayHasKey('correlationId', $response);
        $this->assertArrayHasKey('status', $response);
    }
}
