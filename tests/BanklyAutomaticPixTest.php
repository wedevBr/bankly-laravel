<?php

namespace WeDevBr\Bankly\Tests;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\BanklyAutomaticPix;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\AuthorizationTypeEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\FrequencyTypeEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\RejectReasonEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\RetryPolicyEnum;
use WeDevBr\Bankly\Enums\Pix\AutomaticPix\StatusEnum;
use WeDevBr\Bankly\Requests\AutomaticPix\AuthorizeRequest;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\Amount;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\Creditor;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\Debtor;
use WeDevBr\Bankly\ValueObjects\AutomaticPix\OriginalDebtor;

class BanklyAutomaticPixTest extends TestCase
{
    private BanklyAutomaticPix $banklyAutomaticPix;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auth();
        $this->banklyAutomaticPix = new BanklyAutomaticPix;
    }

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    public function test_get_authorizations_with_minimal_parameters()
    {
        $this->mockHttpResponse([
            'data' => [],
            'pagination' => ['totalPages' => 1],
        ]);

        $result = $this->banklyAutomaticPix->getAuthorizations('12345678901');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
    }

    public function test_get_authorizations_with_all_parameters()
    {
        $initialDate = Carbon::parse('2025-01-01');
        $finalDate = Carbon::parse('2025-12-31');
        $status = StatusEnum::PENDING;

        $this->mockHttpResponse([
            'data' => [
                ['id' => '123', 'status' => 'PENDING'],
            ],
        ]);

        $result = $this->banklyAutomaticPix->getAuthorizations(
            nifNumber: '12345678901',
            initialDate: $initialDate,
            finalDate: $finalDate,
            idRecurrence: 'REC123',
            contractNumber: 'CONTRACT123',
            status: $status,
            page: 2,
            pageSize: 50
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
    }

    public function test_get_authorizations_throws_request_exception()
    {
        $this->expectException(RequestException::class);

        $this->mockHttpError(500);

        $this->banklyAutomaticPix->getAuthorizations('12345678901');
    }

    public function test_authorize_with_valid_request()
    {
        $amount = new Amount('100.00', '50.00', '200.00');
        $creditor = new Creditor('AGENT001', 'John Creditor', '12345678901');
        $debtor = new Debtor(
            '12345',
            '0001',
            '87654321',
            '1234567',
            'Jane Debtor',
            '98765432109'
        );
        $originalDebtor = new OriginalDebtor('Original Debtor', '11111111111');

        $request = new AuthorizeRequest(
            accepted: true,
            authorizationType: AuthorizationTypeEnum::AUT1,
            debtor: $debtor,
            amount: $amount,
            creditor: $creditor,
            originalDebtor: $originalDebtor,
            recurrenceCreationDateTime: Carbon::parse('2025-12-01T00:00:00'),
            retryPolicy: RetryPolicyEnum::ALLOW_3R_7D,
            endToEndId: 'E2EID123',
            scheduledPayment: true,
            contractNumber: 'CONTRACT123',
            description: 'Test payment',
            frequencyType: FrequencyTypeEnum::MNTH,
            initialDateRecurrence: Carbon::parse('2025-01-15T00:00:00'),
            finalDateRecurrence: Carbon::parse('2026-01-15T00:00:00'),
            rejectReason: RejectReasonEnum::AP13
        );

        $this->mockHttpResponse([
            'id' => 'REC123',
            'status' => 'AUTHORIZED',
        ]);

        $result = $this->banklyAutomaticPix->authorize('REC123', fake()->numerify('###########'), $request);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('REC123', $result['id']);
    }

    public function test_authorize_with_minimal_request()
    {
        $debtor = new Debtor(
            '12345',
            '0001',
            '87654321',
            '1234567',
            'Jane Debtor',
            '98765432109'
        );

        $request = new AuthorizeRequest(
            accepted: false,
            authorizationType: AuthorizationTypeEnum::AUT2,
            debtor: $debtor,
            amount: null,
            creditor: null,
            originalDebtor: null,
            recurrenceCreationDateTime: null,
            retryPolicy: null,
            endToEndId: null,
            scheduledPayment: null,
            contractNumber: null,
            description: null,
            frequencyType: null,
            initialDateRecurrence: null,
            finalDateRecurrence: null,
            rejectReason: null
        );

        $this->mockHttpResponse([
            'id' => 'REC456',
            'status' => 'REJECTED',
        ]);

        $result = $this->banklyAutomaticPix->authorize('REC456', fake()->numerify('###########'), $request);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('REJECTED', $result['status']);
    }

    public function test_authorize_replaces_id_recurrence_in_endpoint()
    {
        $debtor = new Debtor(
            '12345',
            '0001',
            '87654321',
            '1234567',
            'Jane Debtor',
            '98765432109'
        );

        $request = new AuthorizeRequest(
            accepted: true,
            authorizationType: AuthorizationTypeEnum::AUT1,
            debtor: $debtor,
            amount: null,
            creditor: null,
            originalDebtor: null,
            recurrenceCreationDateTime: null,
            retryPolicy: null,
            endToEndId: null,
            scheduledPayment: null,
            contractNumber: null,
            description: null,
            frequencyType: null,
            initialDateRecurrence: null,
            finalDateRecurrence: null,
            rejectReason: null
        );

        $this->mockHttpResponse(['status' => 'SUCCESS']);

        $result = $this->banklyAutomaticPix->authorize('CUSTOM_ID_123', fake()->numerify('###########'), $request);

        $this->assertIsArray($result);
    }

    public function test_cancel_with_valid_parameters()
    {
        $this->mockHttpResponse([
            'id' => 'REC123',
            'status' => 'CANCELLED',
        ]);

        $result = $this->banklyAutomaticPix->cancel('REC123', '12345678901');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('CANCELLED', $result['status']);
    }

    public function test_cancel_replaces_id_recurrence_in_endpoint()
    {
        $this->mockHttpResponse(['status' => 'CANCELLED']);

        $result = $this->banklyAutomaticPix->cancel('CUSTOM_CANCEL_ID', '98765432109');

        $this->assertIsArray($result);
    }

    public function test_cancel_sets_correct_headers()
    {
        $this->mockHttpResponse(['status' => 'CANCELLED']);

        $this->banklyAutomaticPix->cancel('REC123', '12345678901');

        $this->assertHeaderWasSet('x-bkly-pix-user-id', '12345678901');
    }

    public function test_get_authorizations_sets_correct_headers()
    {
        $this->mockHttpResponse(['data' => []]);

        $this->banklyAutomaticPix->getAuthorizations('98765432100');

        $this->assertHeaderWasSet('x-bkly-pix-user-id', '98765432100');
    }

    public function test_get_authorizations_with_status_enum()
    {
        $this->mockHttpResponse(['data' => []]);

        $result = $this->banklyAutomaticPix->getAuthorizations(
            nifNumber: '12345678901',
            status: StatusEnum::COMPLETED
        );

        $this->assertIsArray($result);
    }

    public function test_get_authorizations_filters_null_parameters()
    {
        $this->mockHttpResponse(['data' => []]);

        $result = $this->banklyAutomaticPix->getAuthorizations(
            nifNumber: '12345678901',
            initialDate: null,
            finalDate: null,
            idRecurrence: null,
            contractNumber: null,
            status: null
        );

        $this->assertIsArray($result);
    }
}
