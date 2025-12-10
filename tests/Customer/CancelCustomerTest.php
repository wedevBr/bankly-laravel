<?php

namespace WeDevBr\Bankly\Tests\Customer;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Tests\TestCase;

/**
 * CancelCustomerTest class
 */
class CancelCustomerTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path)
    {
        return [
            config('bankly')['api_url']."{$path}" => Http::response([], 202),
        ];
    }

    public function test_success_cancel_customer()
    {
        $documentNumber = '12345678909';
        $reason = 'COMMERCIAL_DISAGREEMENT';

        Http::fake($this->getFakerHttp("/customers/{$documentNumber}/cancel"));

        $client = $this->getBanklyClient();
        $client->cancelCustomer($documentNumber, $reason);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($documentNumber, $reason) {
            $urlOk = Str::contains($request->url(), "/customers/{$documentNumber}/cancel");
            $methodOk = strtoupper($request->method()) === 'PATCH';
            $body = $request->data();

            return $urlOk
                && $methodOk
                && isset($body['reason'])
                && $body['reason'] === $reason;
        });
    }
}
