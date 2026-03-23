<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyKyc;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Inputs\DocumentAnalysis;
use WeDevBr\Bankly\Inputs\DocumentAnalysisCorporationBusiness;

class BanklyKycTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    public function test_send_document_analysis(): void
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

        $this->auth();

        Http::fake([
            config('bankly')['api_url'].'/*' => Http::response([
                'token' => $this->faker->uuid,
            ], 202),
        ]);

        $client = new BanklyKyc;
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

    public function test_send_corporation_business_document_analysis(): void
    {
        $document = $this->getMockBuilder(DocumentAnalysisCorporationBusiness::class)
            ->onlyMethods(['getFileContents'])
            ->getMock();

        $document->expects($this->once())
            ->method('getFileContents')
            ->willReturn('CONTRACT');

        $document->setDocumentType('CONTRACT')
            ->setFilePath('/tmp/contract.pdf')
            ->setFieldName('image');

        $this->auth();

        Http::fake([
            config('bankly')['api_url'].'/*' => Http::response([
                'token' => $this->faker->uuid,
            ], 202),
        ]);

        $client = new BanklyKyc;
        $sendDocument = $client->documentAnalysisCorporationBusiness('00000000000000', $document);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());
            $documentType = $body->where('name', 'documentType')->first();

            return $documentType['contents'] === 'CONTRACT'
                && $request->hasFile('image', 'CONTRACT', 'contract.pdf');
        });

        $this->assertArrayHasKey('token', $sendDocument);
    }

    public function test_success_get_document_analysis(): void
    {
        $this->auth();

        Http::fake([
            config('bankly')['api_url'].'/*' => Http::response([
                'token' => $this->faker->uuid,
                'documentType' => 'RG',
                'documentSide' => 'FRONT',
                'status' => 'ANALYZING',
                'faceMatch' => [],
                'faceDetails' => [],
                'documentDetails' => [],
                'liveness' => [],
                'analyzedAt' => '2021-02-24T17:00:07.614Z',
            ], 202),
        ]);

        $client = new BanklyKyc;
        $document = $client->getDocumentAnalysis('00000000000', [$this->faker->uuid, $this->faker->uuid]);

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

    public function test_success_create_selfie_process(): void
    {
        $this->auth();

        Http::fake([
            config('bankly')['api_url'].'/documents/create-process' => Http::response([
                'userRedirectUrl' => 'https://cadastro.dev.unico.app/process/53060f52-f146-4c12-a234-5bb5031f6f5b',
                'expiresAt' => '2026-03-30T00:00:00Z',
            ], 202),
        ]);

        $client = new BanklyKyc;
        $response = $client->createSelfieProcess('01234567890');

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = $request->data();

            return $request->url() === config('bankly')['api_url'].'/documents/create-process'
                && $body['documentNumber'] === '01234567890'
                && $body['documentType'] === 'SELFIE'
                && $body['documentSide'] === '';
        });

        $this->assertSame(
            'https://cadastro.dev.unico.app/process/53060f52-f146-4c12-a234-5bb5031f6f5b',
            $response['userRedirectUrl']
        );
        $this->assertSame('2026-03-30T00:00:00Z', $response['expiresAt']);
    }
}
