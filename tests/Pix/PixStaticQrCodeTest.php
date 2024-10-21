<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\Types\Pix\Location;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;

/**
 * PixStaticQrCodeTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PixStaticQrCodeTest extends TestCase
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
     * @return AddressingKey
     */
    private function validAddressingKey()
    {
        $addressingKey = new AddressingKey;
        $addressingKey->type = 'CPF';
        $addressingKey->value = '00000000000';

        return $addressingKey;
    }

    /**
     * @return Location
     */
    private function validLocation()
    {
        $addressingKey = new Location;
        $addressingKey->city = 'Vila Velha';
        $addressingKey->zipCode = '29112000';

        return $addressingKey;
    }

    /**
     * @return PixStaticQrCode
     */
    private function validStaticQrCodeData()
    {
        $data = new PixStaticQrCode;
        $data->recipientName = 'Place Holder';
        $data->addressingKey = $this->validAddressingKey();
        $data->location = $this->validLocation();

        return $data;
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
    public function testSuccessStaticQrCode()
    {
        $encoded = 'MDAwMjAxMjYzMzAwMTRici5nb3YuYmNiLnBpeDAxMTE1NjUyNzkzODIxNzUyMDQwMDAwNTMwMzk4NjU0MDQxLjAwN';

        Http::fake($this->getFakerHttp('/pix/qrcodes/static/transfer', [
            'encodedValue' => $encoded,
        ], 200));

        $client = $this->getBanklyClient();
        $response = $client->qrCode('12345678910', $this->validStaticQrCodeData());

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());
            $addressingKey = $body['addressingKey'];

            return $body['recipientName'] === 'Place Holder'
                && $addressingKey['type'] === 'CPF'
                && $addressingKey['value'] === '00000000000';
        });

        $this->assertArrayHasKey('encodedValue', $response);
    }

    /**
     * @return void
     */
    public function testValidateIfAddressingKeyTypeIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this key type is not valid');
        $qrCodeData = $this->validStaticQrCodeData();
        $qrCodeData->addressingKey->type = 'RG';
        $qrCodeData->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfAddressingKeyTypeIsString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type should be a string');
        $qrCodeData = $this->validStaticQrCodeData();
        $qrCodeData->addressingKey->type = null;
        $qrCodeData->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfRecipientNameIsInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('recipient name should be a string');
        $qrCodeData = $this->validStaticQrCodeData();
        $qrCodeData->recipientName = null;
        $qrCodeData->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfAdditionalDataIsToLarge()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('additional data too large');
        $qrCodeData = $this->validStaticQrCodeData();

        $qrCodeData->additionalData = '123123123123123123123131231123123123123123';
        $qrCodeData->additionalData .= '123123123123123123123131231123123123123123';

        $qrCodeData->validate();
    }
}
