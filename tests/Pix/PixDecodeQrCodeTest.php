<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Pix\PixQrCodeData;

/**
 * PixDecodeQrCodeTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class PixDecodeQrCodeTest extends TestCase
{
    use WithFaker;

    /**
     * @var string
     */
    private $encodedValue;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->auth();

        $this->encodedValue = 'MDAwMjAxMjYzMzAwMTRici5nb3YuYmNiLnBpeDAxMTE1NjUyNzkzODIxNzUyMDQwMDAw';
        $this->encodedValue .= 'NTMwMzk4NjU0MDQxLjAwNTgwMkJSNTkxMlZpY3RvciBSYW1vczYwMDlTYW8gUGF1bG82MT';
        $this->encodedValue .= 'A4MDIwMzUwMDA2MjIzMDUxOXRlc3RlUGFnYW1lbnRvUGFwaTI2MzA0RUY3Rg==';
    }

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return PixQrCodeData
     */
    private function validCodedQrCodeData()
    {
        $encodedData = new PixQrCodeData();
        $encodedData->encodedValue = $this->encodedValue;
        $encodedData->documentNumber = '00000000000';

        return $encodedData;
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
    public function testSuccessDecodeStaticQrCode()
    {
        Http::fake($this->getFakerHttp("/pix/qrcodes/decode", [
            "endToEndId" => "E13140088202105201500345922912340",
            "conciliationId" => "testePagamento",
            "addressingKey" => [
                "type" => "CPF",
                "value" => "12345678934"
            ],
            "qrCodeType" => "STATIC",
            "account" => [
                "branch" => "0001",
                "number" => "1234",
                "type" => "CHECKING",
                "holder" => [
                    "type" => "BUSINESS",
                    "documentNumber" => "98590538000106",
                    "name" => "SDB2_TESTE LTDA"
                ],
                "bank" => [
                    "ispb" => "13140088",
                    "compe" => "332",
                    "name" => "Acesso Soluções De Pagamento S.A."
                ]
            ],
            "payment" => [
                "baseValue" => 0.0,
                "interestValue" => 0.0,
                "penaltyValue" => 0.0,
                "discountValue" => 0.0,
                "totalValue" => 1.00
            ],
            "location" => [
                "city" => "Sao Paulo",
                "zipCode" => "02035000"
            ]
        ], 200));

        $client = $this->getBanklyClient();
        $response = $client->qrCodeDecode($this->validCodedQrCodeData());

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            return $body['encodedValue'] === $this->encodedValue;
        });

        $this->assertArrayHasKey('endToEndId', $response);
        $this->assertArrayHasKey('conciliationId', $response);
        $this->assertArrayHasKey('addressingKey', $response);
        $this->assertArrayHasKey('qrCodeType', $response);
        $this->assertArrayHasKey('account', $response);
        $this->assertArrayHasKey('payment', $response);
        $this->assertArrayHasKey('location', $response);
    }

    /**
     * @return void
     */
    public function testValidateIfEncodedValueIsValid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('encoded value should be a string');

        $qrCodeData = $this->validCodedQrCodeData();
        $qrCodeData->encodedValue = null;
        $qrCodeData->validate();
    }

    /**
     * @return void
     */
    public function testValidateIfDocumentNumberIsInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('document number should be a numeric string');

        $qrCodeData = $this->validCodedQrCodeData();
        $qrCodeData->documentNumber = null;
        $qrCodeData->validate();
    }
}
