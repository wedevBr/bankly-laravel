<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Traits\Rest;

/**
 * Class Card
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
     * @param string $password
     * @return array
     */
    public function pciData(string $proxy, string $password)
    {
        $params = [
            'password' => $password
        ];

        return $this->post("/cards/{$proxy}/pci", $params);
    }
}
