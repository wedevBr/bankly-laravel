<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Card\Address;
use WeDevBr\Bankly\Types\Card\Duplicate;

/**
 * DuplicateCardTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class DuplicateCardTest extends TestCase
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
     * @return Address
     */
    private function validAddress()
    {
        $address = new Address;
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
     * @return Duplicate
     */
    private function validDuplicateCard($status = 'LostMyCard', $address = true)
    {
        $duplicateCard = new Duplicate;
        $duplicateCard->status = $status;
        $duplicateCard->documentNumber = '01234567890';
        $duplicateCard->password = '1234';
        if ($address) {
            $duplicateCard->address = $this->validAddress();
        }

        return $duplicateCard;
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path, array $response, int $statusCode = 200)
    {
        return [
            config('bankly')['api_url']."{$path}" => Http::response($response, $statusCode),
        ];
    }

    /**
     * @return void
     */
    public function testSuccessDuplicateCard()
    {
        Http::fake($this->getFakerHttp('/cards/2370021007715002820/duplicate', [
            'proxy' => '2370021007715002820',
            'activateCode' => 'A0DDDC0951D1',
        ], 202));

        $card = new BanklyCard;
        $response = $card->duplicate('2370021007715002820', $this->validDuplicateCard('LostMyCard'));

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());
            $address = $body['address'];

            return $body['documentNumber'] === '01234567890'
                && $body['status'] == 'LostMyCard'
                && $body['password'] === '1234'
                && $address['zipCode'] === '29155909'
                && $address['address'] === 'Rua Olegário Maciel'
                && $address['number'] === '333'
                && $address['complement'] === 'Complement'
                && $address['state'] === 'ES'
                && $address['city'] === 'Vila Velha'
                && $address['neighborhood'] === 'Centro'
                && $address['country'] === 'BR';
        });

        $this->assertArrayHasKey('proxy', $response);
        $this->assertArrayHasKey('activateCode', $response);
    }

    /**
     * @return void
     */
    public function testSuccessDuplicateCardWhitoutAddress()
    {
        Http::fake($this->getFakerHttp('/cards/2370021007715002820/duplicate', [
            'proxy' => '2370021007715002820',
            'activateCode' => 'A0DDDC0951D1',
        ], 202));

        $card = new BanklyCard;
        $response = $card->duplicate('2370021007715002820', $this->validDuplicateCard('LostMyCard', false));

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());
            $address = $body['address'];

            return $body['documentNumber'] === '01234567890'
                && $body['status'] == 'LostMyCard'
                && $body['password'] === '1234';
        });

        $this->assertArrayHasKey('proxy', $response);
        $this->assertArrayHasKey('activateCode', $response);
    }

    /**
     * @return void
     */
    public function testValidateStatus()
    {
        $this->expectException(\InvalidArgumentException::class);
        $message = 'invalid status, needs to be one of these';
        $message .= ' LostMyCard, CardWasStolen, CardWasDamaged, CardNotDelivered, UnrecognizedOnlinePurchase';
        $this->expectExceptionMessage($message);
        $virtualCard = $this->validDuplicateCard('asd');
        $virtualCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateDocumentNumber()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document number should be a numeric string');
        $duplicateCard = $this->validDuplicateCard();
        $duplicateCard->documentNumber = null;
        $duplicateCard->validate();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cpf_cnpj invalid');
        $duplicateCard->document = '12345678901';
    }

    /**
     * @return void
     */
    public function testValidatePassword()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('password should be a numeric string');
        $duplicateCard = $this->validDuplicateCard();
        $duplicateCard->password = 'A123';
        $duplicateCard->validate();
    }

    /**
     * @return void
     */
    public function testValidateAddress()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('zip code should be a numeric string');
        $duplicateCard = $this->validDuplicateCard();
        $duplicateCard->address->zipCode = '';
        $duplicateCard->validate();
    }
}
