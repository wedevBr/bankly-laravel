<?php

namespace WeDevBr\Bankly\Tests;

use Faker\Provider\Uuid;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\BanklyTOTP;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\Types\TOTP\TOTP;

class BanklyTOTPTest extends TestCase
{
    /**
     * @param  object  $app
     */
    protected function getPackageProviders($app): array
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return void
     *
     * @throws RequestException
     *
     * @test
     */
    public function testValidTOTPRegisterEntry()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'hash' => '6c1f6497d8d3bddf02649f8cb34e9e90b916a59e502116c49fcfeef55fc7a8c8',
            'code' => '618467',
        ]));

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');
        $response = $banklyTOTP->createTOTP($this->validTOTP());
        $this->assertArrayHasKey('hash', $response);
        $this->assertArrayHasKey('code', $response);
    }

    /**
     * @throws RequestException
     */
    public function testValidTOTPPortability()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'hash' => '6c1f6497d8d3bddf02649f8cb34e9e90b916a59e502116c49fcfeef55fc7a8c8',
            'code' => '618467',
        ]));

        $totp = $this->validTOTP();
        $totp->operation = TOTP::PORTABILITY;
        $totp->data = ['claim_id' => Uuid::uuid()];

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');
        $response = $banklyTOTP->createTOTP($totp);
        $this->assertArrayHasKey('hash', $response);
        $this->assertArrayHasKey('code', $response);
    }

    /**
     * @throws RequestException
     */
    public function testValidTOTPOwnership()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'hash' => '6c1f6497d8d3bddf02649f8cb34e9e90b916a59e502116c49fcfeef55fc7a8c8',
            'code' => '618467',
        ]));

        $totp = $this->validTOTP();
        $totp->operation = TOTP::OWNERSHIP;
        $totp->data = ['claim_id' => Uuid::uuid()];

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');
        $response = $banklyTOTP->createTOTP($totp);
        $this->assertArrayHasKey('hash', $response);
        $this->assertArrayHasKey('code', $response);
    }

    /**
     * @return void
     *
     * @throws RequestException
     */
    public function testInvalidUserIdTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'code' => 'INVALID_USER_ID',
        ], 400));

        $totp = $this->validTOTP();
        $totp->operation = TOTP::PORTABILITY;
        $totp->data = ['claim_id' => Uuid::uuid()];

        $this->expectException(RequestException::class);
        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');
        $banklyTOTP->createTOTP($totp);
    }

    /**
     * @return void
     *
     * @throws RequestException
     *
     * @test
     */
    public function testInvalidParameterTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'code' => 'INVALID_PARAMETER',
        ], 400));

        $totp = $this->validTOTP();
        $totp->operation = TOTP::PORTABILITY;
        $totp->data = ['claim_id' => Uuid::uuid()];

        $this->expectException(RequestException::class);
        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');
        $banklyTOTP->createTOTP($totp);
    }

    /**
     * @throws RequestException
     *
     * @test
     */
    public function testSuccessVerifyTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'hash' => 'a40296c70ebc780c917aa510aad8aeb3c83c3ce86e0fc3b7149019a75cdb4122',
            'expireInSeconds' => '300',
        ]));

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');
        $response = $banklyTOTP->verifyTOTP(
            'a40296c70ebc780c917aa510aad8aeb3c83c3ce86e0fc3b7149019a75cdb4122',
            '123456'
        );
        $this->assertArrayHasKey('hash', $response);
        $this->assertArrayHasKey('expireInSeconds', $response);
    }

    /**
     * @throws RequestException
     *
     * @test
     */
    public function testInvalidParameterVerifyTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'code' => 'INVALID_PARAMETER',
        ], 400));

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('12345678909');

        $this->expectException(RequestException::class);
        $banklyTOTP->verifyTOTP(
            'a40296c70ebc780c917aa510aad8aeb3c83c3ce86e0fc3b7149019a75cdb4122',
            '123456'
        );
    }

    /**
     * @throws RequestException
     *
     * @test
     */
    public function testInvalidUserIdVerifyTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'code' => 'INVALID_USER_ID',
        ], 400));

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('11122233305');

        $this->expectException(RequestException::class);
        $banklyTOTP->verifyTOTP(
            'a40296c70ebc780c917aa510aad8aeb3c83c3ce86e0fc3b7149019a75cdb4122',
            '123456'
        );
    }

    /**
     * @return void
     *
     * @throws RequestException
     *
     * @test
     */
    public function testNotFoundUserVerifyTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'code' => 'NOT_FOUND',
        ], 404));

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('11122233305');

        $this->expectException(RequestException::class);
        $banklyTOTP->verifyTOTP(
            'a40296c70ebc780c917aa510aad8aeb3c83c3ce86e0fc3b7149019a75cdb4121',
            '123451'
        );
    }

    /**
     * @return void
     *
     * @throws RequestException
     *
     * @test
     */
    public function testInvalidHashVerifyTOTP()
    {
        $this->auth();
        Http::fake($this->getFakerHttp('/totp', [
            'code' => 'ENTRY_TRANSACTIONAL_HASH_INVALID',
        ], 422));

        $banklyTOTP = new BanklyTOTP;
        $banklyTOTP->setDocumentNumber('11122233305');

        $this->expectException(RequestException::class);
        $banklyTOTP->verifyTOTP(
            'a40296c70ebc780c917aa510aad8aeb3c83c3ce86e0fc3b7149019a75cdb4121',
            '123456'
        );
    }

    public function validTOTP(): TOTP
    {
        $addressingKey = new AddressingKey;
        $addressingKey->type = 'PHONE';
        $addressingKey->value = $this->faker->numerify('+55###########');

        $totpType = new TOTP;
        $totpType->context = 'PIX';
        $totpType->operation = TOTP::REGISTER_ENTRY;
        $totpType->data = [
            'addressingKey' => $addressingKey,
        ];

        return $totpType;
    }

    public function getFakerHttp(string $path, array $response, int $statusCode = 200): array
    {
        return [
            config('bankly')['api_url']."{$path}" => Http::response($response, $statusCode),
        ];
    }
}
