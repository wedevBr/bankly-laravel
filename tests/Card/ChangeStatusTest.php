<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Card\ChangeStatus;

/**
 * ChangeStatusTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class ChangeStatusTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth();
    }

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return ChangeStatus
     */
    private function validStatus()
    {
        $changeStatus = new ChangeStatus;
        $changeStatus->password = '1234';
        $changeStatus->status = 'TemporarilyUserLocked';

        return $changeStatus;
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path, array $response, int $statusCode = 200)
    {
        return [
            config('bankly')['api_url']."{$path}" => Http::response($response, $statusCode),
        ];
    }

    /**
     * @return void
     *
     * @throws RequestException
     */
    public function test_success_changed_card_status()
    {
        Http::fake($this->getFakerHttp('/cards/12345678/status', []));

        $card = new BanklyCard;
        $card->changeStatus('12345678', $this->validStatus());

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
            $body = collect($request->data());

            return $body['status'] === 'TemporarilyUserLocked'
                && $body['updateCardBinded'] === false
                && $body['password'] === '1234';
        });
    }

    /**
     * @return void
     */
    public function test_validate_password()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('password should be a numeric string');
        $validStatus = $this->validStatus();
        $validStatus->password = null;
        $validStatus->validate();
    }

    /**
     * @return void
     */
    public function test_validate_empty_status()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('status should be a string');
        $validStatus = $this->validStatus();
        $validStatus->status = null;
        $validStatus->validate();
    }

    /**
     * @return void
     */
    public function test_return_invalid_type_status()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('this status is not valid');
        $validStatus = $this->validStatus();
        $validStatus->status = 'Undefined';
        $validStatus->validate();
    }

    /**
     * @return void
     */
    public function test_return_invalid_update_card_binded()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('update card binded should be a boolean');
        $validStatus = $this->validStatus();
        $validStatus->updateCardBinded = null;
        $validStatus->validate();
    }
}
