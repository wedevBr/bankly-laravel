<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\CardAccountBindingInterface;
use WeDevBr\Bankly\Support\Contracts\CardActivationInterface;
use WeDevBr\Bankly\Support\Contracts\CardAddressInterface;
use WeDevBr\Bankly\Support\Contracts\CardBatchInterface;
use WeDevBr\Bankly\Support\Contracts\CardBindingInterface;
use WeDevBr\Bankly\Support\Contracts\CardDueDateInterface;
use WeDevBr\Bankly\Support\Contracts\CardInterface;
use WeDevBr\Bankly\Support\Contracts\CardLimitInterface;
use WeDevBr\Bankly\Support\Contracts\CardPasswordInterface;
use WeDevBr\Bankly\Support\Contracts\ChangeStatusInterface;
use WeDevBr\Bankly\Support\Contracts\DuplicateCardInterface;
use WeDevBr\Bankly\Support\Contracts\EncryptedPasswordInterface;
use WeDevBr\Bankly\Support\Contracts\ModalityStatusInterface;
use WeDevBr\Bankly\Support\Contracts\WalletInterface;

/**
 * Class BanklyCard
 *
 * @author Rafael Teixeira <rafael.teixeira@wedev.software>
 */
class BanklyCard extends BaseHttpClient
{
    /**
     * Create a new virtual card
     *
     * @return mixed|mixed
     *
     * @throws RequestException
     */
    public function virtualCard(CardInterface $virtualCard): mixed
    {
        return $this->post('/cards/virtual', $virtualCard->toArray(), null, true);
    }

    /**
     * Create a new physical card
     *
     *
     * @throws RequestException
     */
    public function physicalCard(CardInterface $physicalCard): mixed
    {
        return $this->post('/cards/physical', $physicalCard->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function transactions(string $proxy, string $page, int $pageSize, string $startDate, string $endDate): mixed
    {
        $query = [
            'page' => $page,
            'pageSize' => $pageSize,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        return $this->get("/cards/{$proxy}/transactions", $query);
    }

    /**
     * @throws RequestException
     */
    public function duplicate(string $proxy, DuplicateCardInterface $duplicate): mixed
    {
        return $this->post("/cards/{$proxy}/duplicate", $duplicate->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function pciData(string $proxy, CardPasswordInterface $password): mixed
    {
        return $this->post("/cards/{$proxy}/pci", $password->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function getByProxy(string $proxy): mixed
    {
        return $this->get("/cards/{$proxy}");
    }

    /**
     * @throws RequestException
     */
    public function changeStatus(string $proxy, ChangeStatusInterface $changeStatus): mixed
    {
        return $this->patch("/cards/{$proxy}/status", $changeStatus->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function allowContactless(string $proxy, bool $allow): mixed
    {
        $allowContactless = $allow ? 'true' : 'false';

        return $this->patch("/cards/{$proxy}/contactless?allowContactless={$allowContactless}", [], null, true);
    }

    /**
     * @throws RequestException
     */
    public function nextStatus(string $proxy): mixed
    {
        return $this->get("/cards/{$proxy}/nextStatus");
    }

    /**
     * @throws RequestException
     */
    public function cardTracking(string $proxy): mixed
    {
        return $this->get("/cards/{$proxy}/tracking");
    }

    /**
     * @throws RequestException
     */
    public function changePassword(string $proxy, CardPasswordInterface $password): mixed
    {
        return $this->patch("/cards/{$proxy}/password", $password->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function getByDocument(string $documentNumber): mixed
    {
        return $this->get("/cards/document/{$documentNumber}");
    }

    /**
     * @throws RequestException
     */
    public function getByActivateCode(string $activateCode): mixed
    {
        return $this->get("/cards/activateCode/{$activateCode}");
    }

    /**
     * @throws RequestException
     */
    public function getByAccount(string $account): mixed
    {
        return $this->get("/cards/account/{$account}");
    }

    /**
     * @throws RequestException
     */
    public function digitalWallet(WalletInterface $wallet): mixed
    {
        $pathData = $wallet->toArray();
        $endpoint = '/cards-pci/'.$pathData['proxy']
            .'/wallet/'.$pathData['wallet']
            .'/brand/'.$pathData['brand'];

        return $this->post($endpoint, [], null, true);
    }

    /**
     * @throws RequestException
     */
    public function activate(string $proxy, CardActivationInterface $activate): mixed
    {
        return $this->patch("/cards/{$proxy}/activate", $activate->toArray(), null, true);
    }

    /**
     * Create a new multiple (combo) card.
     *
     * @param  Card  $card  The card data.
     *
     * @throws RequestException
     */
    public function multipleCard(CardInterface $card): mixed
    {
        return $this->post('/cards/multiple', $card->toArray(), null, true);
    }

    /**
     * Query card data by PAN.
     *
     * @param  Password  $password  The password/PCI data containing the PAN.
     *
     * @throws RequestException
     */
    public function getByPan(CardPasswordInterface $password): mixed
    {
        return $this->post('/cards-pci/pan', $password->toArray(), null, true);
    }

    /**
     * Change card password with encryption.
     *
     * @param  string  $proxy  Card proxy.
     * @param  EncryptedPasswordInterface  $encryptedPassword  The encrypted password data.
     *
     * @throws RequestException
     */
    public function changeEncryptedPassword(string $proxy, EncryptedPasswordInterface $encryptedPassword): mixed
    {
        return $this->patch("/cards-pci/{$proxy}/password", $encryptedPassword->toArray(), null, true);
    }

    /**
     * Query encrypted card password.
     *
     * @param  string  $proxy  Card proxy.
     *
     * @throws RequestException
     */
    public function getEncryptedPassword(string $proxy): mixed
    {
        return $this->get("/cards-pci/{$proxy}/password");
    }

    /**
     * Change the active credit limit of a postpaid card.
     *
     * @param  string  $proxy  Card proxy.
     * @param  CardLimitInterface  $limit  The limit data.
     *
     * @throws RequestException
     */
    public function updateActiveLimit(string $proxy, CardLimitInterface $limit): mixed
    {
        return $this->patch('/cards/limit', $limit->toArray(), null, true);
    }

    /**
     * Change the invoice due date of a card.
     *
     * @param  string  $proxy  Card proxy.
     * @param  CardDueDateInterface  $dueDate  The due date data.
     *
     * @throws RequestException
     */
    public function updateDueDate(string $proxy, CardDueDateInterface $dueDate): mixed
    {
        return $this->put("/cards/{$proxy}/duedate-expiration", $dueDate->toArray(), null, true);
    }

    /**
     * Update the card delivery address (when card is in custody/in transit).
     *
     * @param  string  $proxy  Card proxy.
     * @param  Address  $address  The address data.
     *
     * @throws RequestException
     */
    public function updateTrackingAddress(string $proxy, CardAddressInterface $address): mixed
    {
        return $this->post("/cards/{$proxy}/tracking/address", $address->toArray(), null, true);
    }

    /**
     * Generate activation data for Apple Pay in-App.
     *
     * @param  Wallet  $wallet  The wallet data containing proxy, wallet type and brand.
     *
     * @throws RequestException
     */
    public function generateActivationData(WalletInterface $wallet): mixed
    {
        $pathData = $wallet->toArray();
        $endpoint = '/cards-pci/'.$pathData['proxy']
            .'/wallet/'.$pathData['wallet']
            .'/brand/'.$pathData['brand']
            .'/activation-data';

        return $this->post($endpoint, [], null, true);
    }

    /**
     * Change the status of a combo card modality (debit or credit).
     *
     * @param  string  $proxy  Card proxy.
     * @param  ModalityStatusInterface  $modalityStatus  The modality status data.
     *
     * @throws RequestException
     */
    public function changeModalityStatus(string $proxy, ModalityStatusInterface $modalityStatus): mixed
    {
        return $this->patch("/cards/{$proxy}/functionalityStatus", $modalityStatus->toArray(), null, true);
    }

    /**
     * Bind a combo card to a bank account.
     *
     * @param  string  $proxy  Card proxy.
     * @param  CardAccountBindingInterface  $accountBinding  The account binding data.
     *
     * @throws RequestException
     */
    public function bindComboToAccount(string $proxy, CardAccountBindingInterface $accountBinding): mixed
    {
        return $this->patch("/cards/{$proxy}/account", $accountBinding->toArray(), null, true);
    }

    /**
     * Create cards in batch (No Name cards).
     *
     * @param  CardBatchInterface  $batch  The batch creation data.
     *
     * @throws RequestException
     */
    public function createBatchCards(CardBatchInterface $batch, ?string $correlationId = null): mixed
    {
        return $this->post('/cards/batches/', $batch->toArray(), $correlationId, true);
    }

    /**
     * Bind a No Name card to an account/document.
     *
     * @param  string  $activateCode  Card activation code.
     * @param  CardBindingInterface  $binding  The binding data.
     *
     * @throws RequestException
     */
    public function bindNoNameCard(string $activateCode, CardBindingInterface $binding): mixed
    {
        return $this->patch("/cards/activateCode/{$activateCode}/binding/", $binding->toArray(), null, true);
    }

    /**
     * Query all card batches.
     *
     * @param  string|null  $companyKey  Company key filter.
     *
     * @throws RequestException
     */
    public function getBatches(?string $companyKey = null): mixed
    {
        $query = [];
        if ($companyKey !== null) {
            $query['companyKey'] = $companyKey;
        }

        return $this->get('/cards/batches', $query);
    }

    /**
     * Query a specific card batch by ID.
     *
     * @param  string  $batchId  Batch identifier.
     *
     * @throws RequestException
     */
    public function getBatchById(string $batchId): mixed
    {
        return $this->get("/cards/batches/{$batchId}");
    }
}
