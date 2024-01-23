<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Traits\Mtls;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\Card\Activate;
use WeDevBr\Bankly\Types\Card\Card;
use WeDevBr\Bankly\Types\Card\ChangeStatus;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Types\Card\Password;
use WeDevBr\Bankly\Types\Card\Wallet;

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
    public function virtualCard(Card $virtualCard): mixed
    {
        return $this->post('/cards/virtual', $virtualCard->toArray(), null, true);
    }

    /**
     * Create a new physical card
     *
     *
     * @throws RequestException
     */
    public function physicalCard(Card $physicalCard): mixed
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
    public function duplicate(string $proxy, Duplicate $duplicate): mixed
    {
        return $this->post("/cards/{$proxy}/duplicate", $duplicate->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function pciData(string $proxy, Password $password): mixed
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
    public function changeStatus(string $proxy, ChangeStatus $changeStatus): mixed
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
    public function changePassword(string $proxy, Password $password): mixed
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
    public function digitalWallet(Wallet $wallet): mixed
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
    public function activate(string $proxy, Activate $activate): mixed
    {
        return $this->patch("/cards/{$proxy}/activate", $activate->toArray(), null, true);
    }
}
