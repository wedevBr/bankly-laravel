<?php

namespace WeDevBr\Bankly\Traits\Core;

use WeDevBr\Bankly\Types\Card\Activate;
use WeDevBr\Bankly\Types\Card\Card as CardType;
use WeDevBr\Bankly\Types\Card\ChangeStatus;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Types\Card\Password;
use WeDevBr\Bankly\Types\Card\Wallet;

trait Card
{
    /**
     * Create a new virtual card
     *
     * @param CardType $virtualCard
     * @return array|mixed
     */
    public function virtualCard(CardType $virtualCard)
    {
        return $this->post('/cards/virtual', $virtualCard->toArray(), null, true);
    }

    /**
     * Create a new physical card
     *
     * @param Card $physicalCard
     * @return array|mixed
     */
    public function physicalCard(Card $physicalCard)
    {
        return $this->post('/cards/physical', $physicalCard->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param string $page
     * @param integer $pageSize
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function transactions(string $proxy, string $page, int $pageSize, string $startDate, string $endDate): array
    {
        $query = [
            'page' => $page,
            'pageSize' => $pageSize,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return $this->get("/cards/{$proxy}/transactions", $query);
    }

    /**
     * @param string $proxy
     * @param Duplicate $duplicate
     * @return array
     */
    public function duplicate(string $proxy, Duplicate $duplicate): array
    {
        return $this->post("/cards/{$proxy}/duplicate", $duplicate->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param Password $password
     * @return array
     */
    public function pciData(string $proxy, Password $password): array
    {
        return $this->post("/cards/{$proxy}/pci", $password->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @return array
     */
    public function getByProxy(string $proxy): array
    {
        return $this->get("/cards/{$proxy}");
    }

    /**
     * @param string $proxy
     * @param ChangeStatus $changeStatus
     * @return array
     */
    public function changeStatus(string $proxy, ChangeStatus $changeStatus): array
    {
        return $this->patch("/cards/{$proxy}/status", $changeStatus->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param bool $allow
     * @return array
     */
    public function allowContactless(string $proxy, bool $allow): array
    {
        $allowContactless = $allow ? 'true' : 'false';
        return $this->patch("/cards/{$proxy}/contactless?allowContactless={$allowContactless}", [], null, true);
    }

    /**
     * @param string $proxy
     * @return array
     */
    public function nextStatus(string $proxy): array
    {
        return $this->get("/cards/{$proxy}/nextStatus");
    }

    /**
     * @param string $proxy
     * @return array
     */
    public function cardTracking(string $proxy): array
    {
        return $this->get("/cards/{$proxy}/tracking");
    }

    /**
     * @param string $proxy
     * @param Password $password
     * @return array
     */
    public function changePassword(string $proxy, Password $password): array
    {
        return $this->patch("/cards/{$proxy}/password", $password->toArray(), null, true);
    }

    /**
     * @param string $documentNumber
     * @return array
     */
    public function getByDocument(string $documentNumber): array
    {
        return $this->get("/cards/document/{$documentNumber}");
    }

    /**
     * @param string $activateCode
     * @return array
     */
    public function getByActivateCode(string $activateCode): array
    {
        return $this->get("/cards/activateCode/{$activateCode}");
    }

    /**
     * @param string $account
     * @return array
     */
    public function getByAccount(string $account): array
    {
        return $this->get("/cards/account/{$account}");
    }

    /**
     * @param Wallet $wallet
     * @return array
     */
    public function digitalWallet(Wallet $wallet): array
    {
        $pathData = $wallet->toArray();
        $endpoint = '/cards-pci/' . $pathData['proxy']
            . '/wallet/' . $pathData['wallet']
            . '/brand/' . $pathData['brand'];

        return $this->post($endpoint, [], null, true);
    }

    /**
     * @param string $proxy
     * @param Activate $activate
     * @return array
     */
    public function activate(string $proxy, Activate $activate): array
    {
        return $this->patch("/cards/{$proxy}/activate", $activate->toArray(), null, true);
    }
}
