<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\BanklyPix;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;
use WeDevBr\Bankly\Types\Pix\Location;

/**
 * PixStaticQrCodeTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PixStaticQrCodeTest extends TestCase
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
     * @return AddressingKey
     */
    private function validAddressingKey()
    {
        $addressingKey = new AddressingKey();
        $addressingKey->type = 'CPF';
        $addressingKey->value = '00000000000';

        return $addressingKey;
    }

    /**
     * @return Location
     */
    private function validLocation()
    {
        $addressingKey = new Location();
        $addressingKey->city = 'Vila Velha';
        $addressingKey->zipCode = '29112000';

        return $addressingKey;
    }

    /**
     * @return PixStaticQrCode
     */
    private function validStaticQrCodeData()
    {
        $data = new PixStaticQrCode();
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
            config('bankly')['api_url'] . "{$path}" => Http::response($response, $statusCode)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessStaticQrCode()
    {
        $encoded = "MDAwMjAxMjYzMzAwMTRici5nb3YuYmNiLnBpeDAxMTE1NjUyNzkzODIxNzUyMDQwMDAwNTMwMzk4NjU0MDQxLjAwN";

        Http::fake($this->getFakerHttp("/baas/pix/qrcodes", [
            'encodedValue' => $encoded,
        ], 200));

        $pix = new BanklyPix();
        $response = $pix->qrCode($this->validStaticQrCodeData());

        Http::assertSent(function ($request) {
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
        $this->expectErrorMessage('this key type is not valid');
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
        $this->expectErrorMessage('type should be a string');
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
        $this->expectErrorMessage('recipient name should be a string');
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
        $this->expectErrorMessage('additional data to large');
        $qrCodeData = $this->validStaticQrCodeData();

        $qrCodeData->additionalData = '123123123123123123123131231123123123123123';
        $qrCodeData->additionalData .= '123123123123123123123131231123123123123123';

        $qrCodeData->validate();
    }
}
