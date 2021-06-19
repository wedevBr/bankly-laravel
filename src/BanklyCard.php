<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Types\Card\Password;
use WeDevBr\Bankly\Types\Card\ChangeStatus;
use WeDevBr\Bankly\Types\Card\Wallet;

/**
 * Class BanklyCard
 * @author Rafael Teixeira <rafael.teixeira@wedev.software>
 * @package WeDevBr\Bankly
 */
class BanklyCard
{
    use Rest;

    /**
     * @param string $clientSecret
     * @param string $clientId
     */
    public function __construct($clientSecret = null, $clientId = null)
    {
        Auth::login()
            ->setClientId($clientId)
            ->setClientId($clientSecret);
    }

    /**
     * @param string $proxy
     * @param string $page
     * @param integer $pageSize
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function transactions(string $proxy, string $page, int $pageSize, string $startDate, string $endDate)
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
    public function duplicate(string $proxy, Duplicate $duplicate)
    {
        return $this->post("/cards/{$proxy}/duplicate", $duplicate->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param Password $password
     * @return array
     */
    public function pciData(string $proxy, Password $password)
    {
        return $this->post("/cards/{$proxy}/pci", $password->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @return array
     */
    public function getByProxy(string $proxy)
    {
        return $this->get("/cards/{$proxy}");
    }

    /**
     * @param string $proxy
     * @param ChangeStatus $changeStatus
     * @return array
     */
    public function changeStatus(string $proxy, ChangeStatus $changeStatus)
    {
        return $this->patch("/cards/{$proxy}/status", $changeStatus->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param bool $allow
     * @return array
     */
    public function allowContactless(string $proxy, bool $allow)
    {
        $allowContactless = $allow ? 'true' : 'false';
        return $this->patch("/cards/{$proxy}/contactless?allowContactless={$allowContactless}", [], null, true);
    }

    /**
     * @param string $proxy
     * @return array
     */
    public function nextStatus(string $proxy)
    {
        return $this->get("/cards/{$proxy}/nextStatus");
    }

    /**
     * @param string $proxy
     * @param Password $password
     * @return array
     */
    public function changePassword(string $proxy, Password $password)
    {
        return $this->patch("/cards/{$proxy}/password", $password->toArray(), null, true);
    }

    /**
     * @param string $documentNumber
     * @return array
     */
    public function getByDocument(string $documentNumber)
    {
        return $this->get("/cards/document/{$documentNumber}");
    }

    /**
     * @param string $activateCode
     * @return array
     */
    public function getByActivateCode(string $activateCode)
    {
        return $this->get("/cards/activateCode/{$activateCode}");
    }

    /**
     * @param string $account
     * @return array
     */
    public function getByAccount(string $account)
    {
        return $this->get("/cards/account/{$account}");
    }

    /**
     * @param Wallet $wallet
     * @return array
     */
    public function digitalWallet(Wallet $wallet)
    {
        $pathData = $wallet->toArray();
        $endpoint = '/cards-pci/' . $pathData['proxy']
            . '/wallet/' . $pathData['wallet']
            . '/brand/' . $pathData['brand'];

        return $this->post($endpoint, [], null, true);
    }
}
