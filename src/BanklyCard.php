<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Types\Card\Password;
use WeDevBr\Bankly\Types\Card\ChangeStatus;
use WeDevBr\Bankly\Types\Card\Wallet;
use WeDevBr\Bankly\Types\Card\Activate;

/**
 * Class BanklyCard
 * @author Rafael Teixeira <rafael.teixeira@wedev.software>
 * @package WeDevBr\Bankly
 */
class BanklyCard
{
    use Rest;

    /**
     * Bankly constructor.
     *
     * @param null|string $mtlsCert
     * @param null|string $mtlsKey
     * @param null|string $mtlsPassphrase
     * @param null|string $apiUrl
     */
    public function __construct(
        string $mtlsCert = null,
        string $mtlsKey = null,
        string $mtlsPassphrase = null,
        string $apiUrl = null
    )
    {
        $this->mtlsCert = $mtlsCert;
        $this->mtlsKey = $mtlsKey;
        $this->mtlsPassphrase = $mtlsPassphrase;
        $this->apiUrl = $apiUrl;
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

    /**
     * @param string $proxy
     * @param Activate $activate
     * @return array
     */
    public function activate(string $proxy, Activate $activate)
    {
        return $this->patch("/cards/{$proxy}/activate", $activate->toArray(), null, true);
    }
}
