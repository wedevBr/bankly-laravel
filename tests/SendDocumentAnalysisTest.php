<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use TypeError;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Inputs\DocumentAnalysis;

/**
 * SendDocumentAnalysisTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class SendDocumentAnalysisTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    public function testSendDocumentAnalysis()
    {
        $client = new Bankly();
        $document = $this->getMockBuilder(DocumentAnalysis::class)
            ->onlyMethods(['getFileContents'])
            ->getMock();

        $document->expects($this->once())
            ->method('getFileContents')
            ->willReturn('DOCRPS');

        $document->setDocumentSide('FRONT')
            ->setDocumentType('RG')
            ->setFilePath('/tmp/10000.jpg')
            ->setFieldName('image');

        Http::fake([
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600
            ], 200),
            config('bankly')['api_url'] . '/*' => Http::response([
                'token' => $this->faker->uuid
            ], 202)
        ]);

        $sendDocument = $client->documentAnalysis('00000000000', $document);

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            $documentType = $body->where('name', 'documentType')->first();
            $documentSide = $body->where('name', 'documentSide')->first();

            return $documentType['contents'] === 'RG'
                && $documentSide['contents'] === 'FRONT'
                && $request->hasFile('image', 'DOCRPS', '10000.jpg');
        });

        $this->assertArrayHasKey('token', $sendDocument);
    }

    /**
     * @return void
     */
    public function testReturnTypeErrorIfIncompatibleDocument()
    {
        $client = new Bankly();
        $document = new \stdClass();

        $this->expectException(TypeError::class);
        $client->documentAnalysis('00000000000000', $document);
    }
}
