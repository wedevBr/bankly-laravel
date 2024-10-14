<?php

/**
 * LegalAgreementTest class
 *
 * PHP version 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Judson Bandeira <judsonmelobandeira@gmail.com>
 * @copyright 2024 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/cda-admin-backend-laravel/
 */

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyLegalAgreement;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Inputs\Acceptance;
use WeDevBr\Bankly\Inputs\Document;
use WeDevBr\Bankly\Types\Billet\BankAccount;

class BanklyLegalAgreementTest extends TestCase
{
    use WithFaker;

    /**
     * @param  object  $app
     */
    protected function getPackageProviders($app): array
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @test
     */
    public function testGetLegalAgreementDocument()
    {
        Http::fake($this->getFakerHttp('/legal-agreements/file*', [
            'type' => 'TERMS_AND_CONDITIONS_OF_USE',
            'contentType' => 'string',
            'file' => 'string',
        ]));
        $this->auth();

        $legalAgreement = new BanklyLegalAgreement();
        $response = $legalAgreement->getLegalAgreementDocument();

        $this->assertArrayHasKey('type', $response);
        $this->assertArrayHasKey('contentType', $response);
        $this->assertArrayHasKey('file', $response);
    }

    /**
     * @test
     */
    public function testAcceptLegalAgreement()
    {
        Http::fake($this->getFakerHttp('/legal-agreements/accept', [
            'id' => 'string',
        ]));

        $bankAccount = new BankAccount();
        $bankAccount->number = $this->faker->numerify('######');
        $bankAccount->branch = $this->faker->numerify('#####');

        $document = new Document();
        $document->value = $this->faker->numerify('###########');

        $acceptance = new Acceptance();
        $acceptance->account = $bankAccount;
        $acceptance->document = $document;

        $legalAgreement = new BanklyLegalAgreement();
        $response = $legalAgreement->acceptLegalAgreement($acceptance);

        Http::assertSent(function (Request $request) use ($bankAccount, $document) {
            return Str::contains($request->url(), '/legal-agreements/accept') &&
                $request->data()['acceptance']['account']['branch'] === $bankAccount->branch &&
                $request->data()['acceptance']['account']['number'] === $bankAccount->number &&
                $request->data()['acceptance']['document']['value'] === $document->value &&
                $request->data()['type'] === 'TERMS_AND_CONDITIONS_OF_USE' &&
                $request->isJson();
        });
        $this->assertArrayHasKey('id', $response);
    }

    public function getFakerHttp(string $path, array $response, int $statusCode = 200): array
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600,
            ], 200),
            config('bankly')['api_url']."{$path}" => Http::response($response, $statusCode),
        ];
    }
}
