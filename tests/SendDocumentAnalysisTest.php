<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
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
 *
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
        $document = $this->getMockBuilder(DocumentAnalysis::class)
            ->onlyMethods(['getFileContents'])
            ->getMock();

        $document->expects($this->once())
            ->method('getFileContents')
            ->willReturn('DOCRPS');

        $document->setDocumentSide('FRONT')
            ->setDocumentType('RG')
            ->setFilePath('/tmp/10000.jpg')
            ->setFieldName('image')
            ->setProvider('BANKLY')
            ->setEncrypted('jwtstring');

        Http::fake([
            config('bankly')['api_url'].'/*' => Http::response([
                'token' => $this->faker->uuid,
            ], 202),
        ]);

        $client = $this->getBanklyClient();

        $sendDocument = $client->documentAnalysis('00000000000', $document);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            $documentType = $body->where('name', 'documentType')->first();
            $documentSide = $body->where('name', 'documentSide')->first();
            $documentProvider = $body->where('name', 'provider')->first();
            $documentProviderMetadata = $body->where('name', 'providerMetadata')->first();

            return $documentType['contents'] === 'RG'
                && $documentSide['contents'] === 'FRONT'
                && $documentProvider['contents'] === 'BANKLY'
                && empty($documentProviderMetadata['contents'])
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
