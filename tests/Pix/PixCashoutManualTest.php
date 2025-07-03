<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\AddressingAccount;
use WeDevBr\Bankly\Types\Pix\Bank;
use WeDevBr\Bankly\Types\Pix\BankAccount;
use WeDevBr\Bankly\Types\Pix\PixCashoutManual;

/**
 * PixCashoutTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PixCashoutManualTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return BankAccount
     */
    public function validSender()
    {
        $addressingAccount = new AddressingAccount;
        $addressingAccount->branch = '0001';
        $addressingAccount->number = '1111';
        $addressingAccount->type = 'CHECKING';

        $bank = new Bank;
        $bank->ispb = '11112222';
        $bank->compe = '323';
        $bank->name = 'Banco Test S.A.';

        $sender = new BankAccount;
        $sender->documentNumber = '12345678909';
        $sender->name = 'Jhon Smith';
        $sender->account = $addressingAccount;
        $sender->bank = $bank;

        return $sender;
    }

    /**
     * @return BankAccount
     */
    public function validRecipient()
    {
        $addressingAccount = new AddressingAccount;
        $addressingAccount->branch = '0002';
        $addressingAccount->number = '2222';
        $addressingAccount->type = 'CHECKING';

        $bank = new Bank;
        $bank->ispb = '00000000';
        $bank->compe = '001';
        $bank->name = 'Banco BB S.A.';

        $recipient = new BankAccount;
        $recipient->documentNumber = '12345678909';
        $recipient->name = 'Sara Smith';
        $recipient->account = $addressingAccount;
        $recipient->bank = $bank;

        return $recipient;
    }

    /**
     * @return PixCashoutManual
     */
    public function validPixCashout()
    {
        $pixCashout = new PixCashoutManual;
        $pixCashout->amount = '83.23';
        $pixCashout->description = 'Mercado';
        $pixCashout->sender = $this->validSender();
        $pixCashout->recipient = $this->validRecipient();

        return $pixCashout;
    }

    /**
     * @return array
     */
    public function getFakerHttp(array $response, int $statusCode) {}

    public function successResponse()
    {
        $response = [
            'amount' => 99.98,
            'description' => 'string',
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
                'documentNumber' => 'string',
                'name' => 'string',
            ],
            'authenticationCode' => 'string',
        ];

        Http::fake([
            config('bankly')['api_url'].'/pix/cash-out' => Http::response($response, 202),
        ]);
    }

    /**
     * @return void
     */
    public function test_success_pix_cashout()
    {
        $this->successResponse();

        $client = $this->getBanklyClient();
        $response = $client->pixCashout($this->validPixCashout(), $this->faker->uuid);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            $sender = $body['sender'];
            $recipient = $body['recipient'];

            return $body['amount'] === '83.23'
                && $body['description'] === 'Mercado'
                && $body['initializationType'] === 'Manual'

                && $sender['account']['branch'] === '0001'
                && $sender['account']['number'] === '1111'
                && $sender['account']['type'] === 'CHECKING'
                && $sender['bank']['ispb'] === '11112222'
                && $sender['bank']['compe'] === '323'
                && $sender['bank']['name'] === 'Banco Test S.A.'
                && $sender['documentNumber'] === '12345678909'
                && $sender['name'] === 'Jhon Smith'

                && $recipient['account']['branch'] === '0002'
                && $recipient['account']['number'] === '2222'
                && $recipient['account']['type'] === 'CHECKING'
                && $recipient['bank']['ispb'] === '00000000'
                && $recipient['bank']['compe'] === '001'
                && $recipient['bank']['name'] === 'Banco BB S.A.'
                && $recipient['documentNumber'] === '12345678909'
                && $recipient['name'] === 'Sara Smith';
        });

        $this->assertArrayHasKey('amount', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('sender', $response);
        $this->assertArrayHasKey('recipient', $response);
        $this->assertArrayHasKey('authenticationCode', $response);
    }
}
