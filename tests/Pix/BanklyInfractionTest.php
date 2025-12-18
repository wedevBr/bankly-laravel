<?php

namespace WeDevBr\Bankly\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyInfraction;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Enums\Pix\InfractionPix\InfractionSituationEnum;
use WeDevBr\Bankly\Types\Pix\PixInfraction;

class BanklyInfractionTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    public function test_create_infraction_sends_correct_request_and_returns_response(): void
    {
        $this->auth();

        $branch = '0001';
        $account = '1234567';
        $nifNumber = '12345678909';

        $pixInfraction = new PixInfraction();
        $pixInfraction->endToEndId = 'E2E1234567890';
        $pixInfraction->description = 'Suspected scam';
        $pixInfraction->requestDate = Carbon::create(2025, 1, 2, 3, 4, 5, 'UTC');
        $pixInfraction->situation = InfractionSituationEnum::Scam;
        $pixInfraction->branch = $branch;
        $pixInfraction->account = $account;

        $expectedUrl = config('bankly')['api_url'] . "/pix/branches/{$branch}/accounts/{$account}/infractions";

        $fakeResponse = [
            'protocol' => [
                'number' => '20250712175650999455',
                'openDate' => '2025-07-12T17:56:50.999Z',
            ],
        ];

        Http::fake([
            $expectedUrl => Http::response($fakeResponse, 201),
        ]);

        $client = new BanklyInfraction();
        $response = $client->createInfraction($nifNumber, $pixInfraction);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($expectedUrl, $pixInfraction, $nifNumber) {
            $body = $request->data();

            return $request->url() === $expectedUrl
                && $request->method() === 'POST'
                && $request->hasHeader('x-bkly-pix-user-id', $nifNumber)
                && $body['endToEndId'] === $pixInfraction->endToEndId
                && $body['description'] === $pixInfraction->description
                && $body['requestDate'] === $pixInfraction->requestDate->toIso8601String()
                && $body['situation'] === $pixInfraction->situation->value;
        });

        $this->assertSame($fakeResponse, $response);
    }
}
