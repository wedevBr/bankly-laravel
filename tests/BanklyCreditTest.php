<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyCredit;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Types\Credit\CreditAcceptance;
use WeDevBr\Bankly\Types\Credit\CreditAnalysis;
use WeDevBr\Bankly\Types\Credit\CreditLimitAcceptance;
use WeDevBr\Bankly\Types\Credit\CreditPreAnalysis;
use WeDevBr\Bankly\Types\Credit\CreditReanalysis;

class BanklyCreditTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [BanklyServiceProvider::class];
    }

    private function fakeHttp(string $path, array $response, int $status = 200): array
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600,
            ], 200),
            config('bankly')['api_url'].$path => Http::response($response, $status),
        ];
    }

    #[Test]
    public function test_create_credit_analysis(): void
    {
        Http::fake($this->fakeHttp('/cards/credits/customers', [
            'companyKey' => 'TEST',
            'document' => '47742663023',
            'contract' => '000010',
        ], 202));
        $this->auth();

        $credit = new BanklyCredit;
        $analysis = new CreditAnalysis;
        $analysis->documentNumber = '47742663023';
        $analysis->name = 'Nísia Floresta';
        $analysis->programId = 111;
        $analysis->motherName = 'Maria Floresta';
        $analysis->birthDate = '1980-01-01';
        $analysis->email = 'nisia@example.com';
        $analysis->profession = 'Engineer';
        $analysis->maritalStatus = 'Single';
        $analysis->academicDegree = 'Bachelor';
        $analysis->incomeBracket = 'A';
        $analysis->sex = 'Female';
        $response = $credit->createCreditAnalysis($analysis);

        $this->assertEquals('TEST', $response['companyKey']);
        $this->assertEquals('000010', $response['contract']);
    }

    #[Test]
    public function test_create_pre_analysis(): void
    {
        Http::fake($this->fakeHttp('/cards/credits/pre-analysis', [
            'companyKey' => 'TEST',
            'document' => '47742663023',
        ], 202));
        $this->auth();

        $credit = new BanklyCredit;
        $preAnalysis = new CreditPreAnalysis;
        $preAnalysis->documentNumber = '47742663023';
        $preAnalysis->programId = 111;
        $preAnalysis->name = 'Nísia Floresta';
        $response = $credit->createPreAnalysis($preAnalysis);

        $this->assertEquals('TEST', $response['companyKey']);
    }

    #[Test]
    public function test_accept_contract_proposal(): void
    {
        Http::fake($this->fakeHttp('/cards/credits/terms-and-conditions/acceptance', [
            'id' => 'abc123',
        ], 200));
        $this->auth();

        $credit = new BanklyCredit;
        $acceptance = new CreditAcceptance;
        $acceptance->contract = '000010';
        $acceptance->documentNumber = '47742663023';
        $acceptance->dataHash = 'hash123';
        $response = $credit->acceptContractProposal($acceptance);

        $this->assertEquals('abc123', $response['id']);
    }

    #[Test]
    public function test_get_contract(): void
    {
        Http::fake($this->fakeHttp('/cards/credits/document/47742663023/contracts/000010', [
            'contract' => '000010',
            'status' => 'Approved',
        ], 200));
        $this->auth();

        $credit = new BanklyCredit;
        $response = $credit->getContract('47742663023', '000010');

        $this->assertEquals('000010', $response['contract']);
        $this->assertEquals('Approved', $response['status']);
    }

    #[Test]
    public function test_reanalyze_contract(): void
    {
        Http::fake($this->fakeHttp('/cards/credits/document/47742663023/contract/000010/reanalyze', [
            'contract' => '000010',
        ], 200));
        $this->auth();

        $credit = new BanklyCredit;
        $reanalysis = new CreditReanalysis;
        $reanalysis->documentNumber = '47742663023';
        $reanalysis->programId = 111;
        $reanalysis->name = 'Nísia Floresta';
        $response = $credit->reanalyzeContract('47742663023', '000010', $reanalysis);

        $this->assertEquals('000010', $response['contract']);
    }

    #[Test]
    public function test_accept_new_limit(): void
    {
        Http::fake($this->fakeHttp('/cards/credits/document/47742663023/contract/000010/acceptance', [
            'contract' => '000010',
            'accepted' => true,
        ], 200));
        $this->auth();

        $credit = new BanklyCredit;
        $acceptance = new CreditLimitAcceptance;
        $acceptance->documentNumber = '47742663023';
        $acceptance->contract = '000010';
        $acceptance->accepted = true;
        $response = $credit->acceptNewLimit('47742663023', '000010', $acceptance);

        $this->assertTrue($response['accepted']);
    }
}
