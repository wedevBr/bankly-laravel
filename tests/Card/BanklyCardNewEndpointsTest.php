<?php

namespace WeDevBr\Bankly\Tests\Card;

use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use WeDevBr\Bankly\BanklyCard;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Tests\TestCase;
use WeDevBr\Bankly\Types\Card\Address;
use WeDevBr\Bankly\Types\Card\Card;
use WeDevBr\Bankly\Types\Card\CardAccountBinding;
use WeDevBr\Bankly\Types\Card\CardBatch;
use WeDevBr\Bankly\Types\Card\CardBinding;
use WeDevBr\Bankly\Types\Card\CardDueDate;
use WeDevBr\Bankly\Types\Card\CardLimit;
use WeDevBr\Bankly\Types\Card\EncryptedPassword;
use WeDevBr\Bankly\Types\Card\ModalityStatus;
use WeDevBr\Bankly\Types\Card\Password;

class BanklyCardNewEndpointsTest extends TestCase
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
    public function test_multiple_card(): void
    {
        Http::fake($this->fakeHttp('/cards/multiple', [
            'proxy' => '123456',
            'cardType' => 'Physical',
        ]));
        $this->auth();

        $card = new Card;
        $card->documentNumber = '01234567890';
        $card->cardName = 'Test User';
        $card->alias = 'My Card';
        $card->bankAgency = '0001';
        $card->bankAccount = '12345';
        $card->programId = '1';
        $card->password = '1234';
        $card->type = 'Physical';
        $card->address = new Address;
        $card->address->zipCode = '05402100';
        $card->address->address = 'Rua 6 de Março';
        $card->address->number = '2500';
        $card->address->neighborhood = 'Centro';
        $card->address->city = 'São Paulo';
        $card->address->state = 'SP';
        $card->address->country = 'Brasil';

        $banklyCard = new BanklyCard;
        $response = $banklyCard->multipleCard($card);

        $this->assertEquals('123456', $response['proxy']);
    }

    #[Test]
    public function test_get_by_pan(): void
    {
        Http::fake($this->fakeHttp('/cards-pci/pan', [
            'proxy' => '123456',
            'lastFourDigits' => '4321',
        ]));
        $this->auth();

        $password = new Password;
        $password->password = '1234';

        $banklyCard = new BanklyCard;
        $response = $banklyCard->getByPan($password);

        $this->assertEquals('123456', $response['proxy']);
    }

    #[Test]
    public function test_change_encrypted_password(): void
    {
        Http::fake($this->fakeHttp('/cards-pci/123456/password', [], 204));
        $this->auth();

        $banklyCard = new BanklyCard;
        $encryptedPassword = new EncryptedPassword;
        $encryptedPassword->encryptedPassword = 'abc123';
        $banklyCard->changeEncryptedPassword('123456', $encryptedPassword);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_get_encrypted_password(): void
    {
        Http::fake($this->fakeHttp('/cards-pci/123456/password', [
            'password' => 'encrypted_value',
        ]));
        $this->auth();

        $banklyCard = new BanklyCard;
        $response = $banklyCard->getEncryptedPassword('123456');

        $this->assertEquals('encrypted_value', $response['password']);
    }

    #[Test]
    public function test_update_active_limit(): void
    {
        Http::fake($this->fakeHttp('/cards/limit', [], 204));
        $this->auth();

        $banklyCard = new BanklyCard;
        $cardLimit = new CardLimit;
        $cardLimit->proxy = '123456';
        $cardLimit->limit = 5000;
        $banklyCard->updateActiveLimit('123456', $cardLimit);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_update_due_date(): void
    {
        Http::fake($this->fakeHttp('/cards/123456/duedate-expiration', [], 204));
        $this->auth();

        $banklyCard = new BanklyCard;
        $cardDueDate = new CardDueDate;
        $cardDueDate->dueDate = 10;
        $banklyCard->updateDueDate('123456', $cardDueDate);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_update_tracking_address(): void
    {
        Http::fake($this->fakeHttp('/cards/123456/tracking/address', [], 200));
        $this->auth();

        $banklyCard = new BanklyCard;
        $address = new Address;
        $address->zipCode = '05402100';
        $address->address = 'Rua 6 de Março';
        $address->number = '2500';
        $address->neighborhood = 'Centro';
        $address->city = 'São Paulo';
        $address->state = 'SP';
        $address->country = 'Brasil';
        $banklyCard->updateTrackingAddress('123456', $address);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_change_modality_status(): void
    {
        Http::fake($this->fakeHttp('/cards/123456/functionalityStatus', [], 204));
        $this->auth();

        $banklyCard = new BanklyCard;
        $modalityStatus = new ModalityStatus;
        $modalityStatus->functionality = 'Debit';
        $modalityStatus->status = 'Enabled';
        $banklyCard->changeModalityStatus('123456', $modalityStatus);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_bind_combo_to_account(): void
    {
        Http::fake($this->fakeHttp('/cards/123456/account', [], 204));
        $this->auth();

        $banklyCard = new BanklyCard;
        $accountBinding = new CardAccountBinding;
        $accountBinding->bankAgency = '0001';
        $accountBinding->bankAccount = '12345';
        $accountBinding->documentNumber = '01234567890';
        $banklyCard->bindComboToAccount('123456', $accountBinding);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_create_batch_cards(): void
    {
        Http::fake($this->fakeHttp('/cards/batches*', [
            'batchId' => 'batch123',
        ]));
        $this->auth();

        $banklyCard = new BanklyCard;
        $batch = new CardBatch;
        $batch->quantity = 10;
        $batch->programId = 1;
        $response = $banklyCard->createBatchCards($batch);

        $this->assertEquals('batch123', $response['batchId']);
    }

    #[Test]
    public function test_bind_no_name_card(): void
    {
        Http::fake($this->fakeHttp('/cards/activateCode/ABC123/binding*', [], 204));
        $this->auth();

        $banklyCard = new BanklyCard;
        $binding = new CardBinding;
        $binding->documentNumber = '01234567890';
        $binding->bankAgency = '0001';
        $binding->bankAccount = '12345';
        $banklyCard->bindNoNameCard('ABC123', $binding);
        $this->assertTrue(true);
    }

    #[Test]
    public function test_get_batches(): void
    {
        Http::fake($this->fakeHttp('/cards/batches*', [
            ['batchId' => 'batch1'],
            ['batchId' => 'batch2'],
        ]));
        $this->auth();

        $banklyCard = new BanklyCard;
        $response = $banklyCard->getBatches();

        $this->assertIsArray($response);
    }

    #[Test]
    public function test_get_batch_by_id(): void
    {
        Http::fake($this->fakeHttp('/cards/batches/batch123', [
            'batchId' => 'batch123',
            'status' => 'Completed',
        ]));
        $this->auth();

        $banklyCard = new BanklyCard;
        $response = $banklyCard->getBatchById('batch123');

        $this->assertEquals('batch123', $response['batchId']);
    }
}
