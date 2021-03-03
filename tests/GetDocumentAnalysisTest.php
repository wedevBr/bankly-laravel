<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;

/**
 * GetDocumentAnalysisTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class GetDocumentAnalysisTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return array
     */
    public function getFakerHttp()
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600
            ], 200),
            config('bankly')['api_url'] . '/*' => Http::response([
                'token' => $this->faker->uuid,
                'documentType' => 'RG',
                'documentSide' => 'FRONT',
                'status' => 'ANALYZING',
                'faceMatch' => [],
                'faceDetails' => [],
                'documentDetails' => [],
                'liveness' => [],
                'analyzedAt' => '2021-02-24T17:00:07.614Z'
            ], 202)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessGetDocumentAnalysis()
    {
        $client = new Bankly();

        Http::fake($this->getFakerHttp());

        $token1 = $this->faker->uuid;
        $token2 = $this->faker->uuid;
        $document = $client->getDocumentAnalysis('00000000000', [$token1, $token2]);

        $this->assertArrayHasKey('token', $document);
        $this->assertArrayHasKey('documentType', $document);
        $this->assertArrayHasKey('documentSide', $document);
        $this->assertArrayHasKey('status', $document);
        $this->assertArrayHasKey('faceMatch', $document);
        $this->assertArrayHasKey('faceDetails', $document);
        $this->assertArrayHasKey('documentDetails', $document);
        $this->assertArrayHasKey('liveness', $document);
        $this->assertArrayHasKey('analyzedAt', $document);
    }

    /**
     * @return void
     */
    public function testSuccessGetDocumentAnalysisWithoutToken()
    {
        $client = new Bankly();

        Http::fake($this->getFakerHttp());

        $document = $client->getDocumentAnalysis('00000000000', [], 'DETAILED');

        $this->assertArrayHasKey('token', $document);
        $this->assertArrayHasKey('documentType', $document);
        $this->assertArrayHasKey('documentSide', $document);
        $this->assertArrayHasKey('status', $document);
        $this->assertArrayHasKey('faceMatch', $document);
        $this->assertArrayHasKey('faceDetails', $document);
        $this->assertArrayHasKey('documentDetails', $document);
        $this->assertArrayHasKey('liveness', $document);
        $this->assertArrayHasKey('analyzedAt', $document);
    }
}
