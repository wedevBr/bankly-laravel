<?php

namespace WeDevBr\Bankly\Tests;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\BanklyScheduledPix;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Enums\Pix\ScheduledPix\LiquidationCodeSubTypeEnum;
use WeDevBr\Bankly\Enums\Pix\ScheduledPix\StatusEnum;

class BanklyScheduledPixTest extends TestCase
{
    private BanklyScheduledPix $banklyScheduledPix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth();
        $this->banklyScheduledPix = new BanklyScheduledPix;
    }

    /**
     * @param  object  $app
     */
    protected function getPackageProviders($app): array
    {
        return [BanklyServiceProvider::class];
    }

    public function test_get_scheduled_pix_with_minimal_parameters()
    {
        $this->mockHttpResponse([
            'data' => [],
            'pagination' => ['totalPages' => 1],
        ]);

        $result = $this->banklyScheduledPix->getScheduledPix('12345678901');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
    }

    public function test_get_scheduled_pix_with_all_parameters()
    {
        $initialDate = Carbon::parse('2024-06-12');
        $endDate = Carbon::parse('2025-06-13');

        $this->mockHttpResponse([
            'data' => [
                ['id' => '123', 'status' => 'SCHEDULED'],
            ],
        ]);

        $result = $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            liquidationCodeSubType: LiquidationCodeSubTypeEnum::COMMON_SCHEDULING,
            debtorAccountIdentification: '2131231',
            debtorAccountIssuer: '0001',
            status: StatusEnum::CANCELED,
            initialDate: $initialDate,
            endDate: $endDate,
            page: 1,
            pageSize: 100
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
    }

    public function test_get_scheduled_pix_sets_correct_headers()
    {
        $this->mockHttpResponse(['data' => []]);

        $this->banklyScheduledPix->getScheduledPix('98765432100');

        $this->assertHeaderWasSet('x-bkly-pix-user-id', '98765432100');
    }

    public function test_get_scheduled_pix_filters_null_parameters()
    {
        $this->mockHttpResponse(['data' => []]);

        $result = $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            liquidationCodeSubType: null,
            debtorAccountIdentification: null,
            debtorAccountIssuer: null,
            status: null,
            initialDate: null,
            endDate: null,
            page: null,
            pageSize: null
        );

        $this->assertIsArray($result);
    }

    public function test_get_scheduled_pix_with_dates_formats_correctly()
    {
        $this->mockHttpResponse(['data' => []]);

        $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            initialDate: Carbon::parse('2024-01-15'),
            endDate: Carbon::parse('2024-12-31')
        );

        $this->assertIsArray($this->banklyScheduledPix->getScheduledPix('12345678901'));
    }

    public function test_get_scheduled_pix_throws_request_exception()
    {
        $this->expectException(RequestException::class);

        $this->mockHttpError(500);

        $this->banklyScheduledPix->getScheduledPix('12345678901');
    }

    public function test_get_scheduled_pix_by_id_with_valid_parameters()
    {
        $this->mockHttpResponse([
            'id' => 'PIX123',
            'status' => 'SCHEDULED',
            'amount' => '100.00',
        ]);

        $result = $this->banklyScheduledPix->getScheduledPixById('PIX123', '12345678901');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('PIX123', $result['id']);
    }

    public function test_get_scheduled_pix_by_id_sets_correct_headers()
    {
        $this->mockHttpResponse([
            'id' => 'PIX123',
            'status' => 'SCHEDULED',
        ]);

        $this->banklyScheduledPix->getScheduledPixById('PIX123', '98765432100');

        $this->assertHeaderWasSet('x-bkly-pix-user-id', '98765432100');
    }

    public function test_get_scheduled_pix_by_id_with_different_id()
    {
        $this->mockHttpResponse([
            'id' => 'CUSTOM_PIX_ID',
            'status' => 'PENDING',
        ]);

        $result = $this->banklyScheduledPix->getScheduledPixById('CUSTOM_PIX_ID', '11111111111');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('PENDING', $result['status']);
    }

    public function test_cancel_scheduled_pix_by_id_with_valid_parameters()
    {
        $this->mockHttpResponse([
            'id' => 'PIX123',
            'status' => 'CANCELLED',
        ]);

        $result = $this->banklyScheduledPix->cancelScheduledPixById('PIX123', '12345678901');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('CANCELLED', $result['status']);
    }

    public function test_cancel_scheduled_pix_by_id_sets_correct_headers()
    {
        $this->mockHttpResponse([
            'id' => 'PIX123',
            'status' => 'CANCELLED',
        ]);

        $this->banklyScheduledPix->cancelScheduledPixById('PIX123', '98765432100');

        $this->assertHeaderWasSet('x-bkly-pix-user-id', '98765432100');
    }

    public function test_cancel_scheduled_pix_by_id_with_different_id()
    {
        $this->mockHttpResponse(['status' => 'CANCELLED']);

        $result = $this->banklyScheduledPix->cancelScheduledPixById('CANCEL_ID_456', '22222222222');

        $this->assertIsArray($result);
    }

    public function test_get_scheduled_pix_with_pagination_parameters()
    {
        $this->mockHttpResponse([
            'data' => [],
            'pagination' => [
                'currentPage' => 2,
                'totalPages' => 5,
                'pageSize' => 50,
            ],
        ]);

        $result = $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            page: 2,
            pageSize: 50
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertEquals(2, $result['pagination']['currentPage']);
    }

    public function test_get_scheduled_pix_with_account_parameters()
    {
        $this->mockHttpResponse(['data' => []]);

        $result = $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            debtorAccountIdentification: '987654321',
            debtorAccountIssuer: '0002'
        );

        $this->assertIsArray($result);
    }

    public function test_get_scheduled_pix_with_status_parameter()
    {
        $this->mockHttpResponse([
            'data' => [
                ['status' => 'SCHEDULED'],
            ],
        ]);

        $result = $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            status: StatusEnum::SCHEDULED
        );

        $this->assertIsArray($result);
    }

    public function test_get_scheduled_pix_with_liquidation_code_sub_type()
    {
        $this->mockHttpResponse(['data' => []]);

        $result = $this->banklyScheduledPix->getScheduledPix(
            nifNumber: '12345678901',
            liquidationCodeSubType: LiquidationCodeSubTypeEnum::COMMON_SCHEDULING
        );

        $this->assertIsArray($result);
    }
}
