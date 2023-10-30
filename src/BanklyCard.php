<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\Card\Duplicate;
use WeDevBr\Bankly\Types\Card\Password;
use WeDevBr\Bankly\Types\Card\ChangeStatus;
use WeDevBr\Bankly\Types\Card\Wallet;
use WeDevBr\Bankly\Types\Card\Activate;
use WeDevBr\Bankly\Types\Card\Card;

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
     * @param null|string $mtlsPassphrase
     */
    public function __construct(string $mtlsPassphrase = null)
    {
        $this->mtlsCert = config('bankly')['mtls_cert_path'] ?? null;
        $this->mtlsKey = config('bankly')['mtls_key_path'] ?? null;
        $this->mtlsPassphrase = $mtlsPassphrase;
    }

    /**
     * @param string $passPhrase
     * @return self
     */
    public function setPassphrase(string $passPhrase): self
    {
        $this->mtlsPassphrase = $passPhrase;
        return $this;
    }

    /**
     * Set the cert.crt file path
     * @param string $path
     * @return self
     */
    public function setCertPath(string $path): self
    {
        $this->mtlsCert = $path;
        return $this;
    }

    /**
     * Set the cert.pem file path
     * @param string $path
     * @return self
     */
    public function setKeyPath(string $path): self
    {
        $this->mtlsKey = $path;
        return $this;
    }

    /**
     * Create a new virtual card
     *
     * @param Card $virtualCard
     * @return mixed|mixed
     * @throws RequestException
     */
    public function virtualCard(Card $virtualCard): mixed
    {
        return $this->post('/cards/virtual', $virtualCard->toArray(), null, true);
    }

    /**
     * Create a new physical card
     *
     * @param Card $physicalCard
     * @return mixed|mixed
     * @throws RequestException
     */
    public function physicalCard(Card $physicalCard): mixed
    {
        return $this->post('/cards/physical', $physicalCard->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param string $page
     * @param integer $pageSize
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     * @throws RequestException
     */
    public function transactions(string $proxy, string $page, int $pageSize, string $startDate, string $endDate): mixed
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
     * @return mixed
     * @throws RequestException
     */
    public function duplicate(string $proxy, Duplicate $duplicate): mixed
    {
        return $this->post("/cards/{$proxy}/duplicate", $duplicate->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param Password $password
     * @return mixed
     * @throws RequestException
     */
    public function pciData(string $proxy, Password $password): mixed
    {
        return $this->post("/cards/{$proxy}/pci", $password->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @return mixed
     * @throws RequestException
     */
    public function getByProxy(string $proxy): mixed
    {
        return $this->get("/cards/{$proxy}");
    }

    /**
     * @param string $proxy
     * @param ChangeStatus $changeStatus
     * @return mixed
     * @throws RequestException
     */
    public function changeStatus(string $proxy, ChangeStatus $changeStatus): mixed
    {
        return $this->patch("/cards/{$proxy}/status", $changeStatus->toArray(), null, true);
    }

    /**
     * @param string $proxy
     * @param bool $allow
     * @return mixed
     * @throws RequestException
     */
    public function allowContactless(string $proxy, bool $allow): mixed
    {
        $allowContactless = $allow ? 'true' : 'false';
        return $this->patch("/cards/{$proxy}/contactless?allowContactless={$allowContactless}", [], null, true);
    }

    /**
     * @param string $proxy
     * @return mixed
     * @throws RequestException
     */
    public function nextStatus(string $proxy): mixed
    {
        return $this->get("/cards/{$proxy}/nextStatus");
    }

    /**
     * @param string $proxy
     * @return mixed
     * @throws RequestException
     */
    public function cardTracking(string $proxy): mixed
    {
        return $this->get("/cards/{$proxy}/tracking");
    }

    /**
     * @param string $proxy
     * @param Password $password
     * @return mixed
     * @throws RequestException
     */
    public function changePassword(string $proxy, Password $password): mixed
    {
        return $this->patch("/cards/{$proxy}/password", $password->toArray(), null, true);
    }

    /**
     * @param string $documentNumber
     * @return mixed
     * @throws RequestException
     */
    public function getByDocument(string $documentNumber): mixed
    {
        return $this->get("/cards/document/{$documentNumber}");
    }

    /**
     * @param string $activateCode
     * @return mixed
     * @throws RequestException
     */
    public function getByActivateCode(string $activateCode): mixed
    {
        return $this->get("/cards/activateCode/{$activateCode}");
    }

    /**
     * @param string $account
     * @return mixed
     * @throws RequestException
     */
    public function getByAccount(string $account): mixed
    {
        return $this->get("/cards/account/{$account}");
    }

    /**
     * @param Wallet $wallet
     * @return mixed
     * @throws RequestException
     */
    public function digitalWallet(Wallet $wallet): mixed
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
     * @return mixed
     * @throws RequestException
     */
    public function activate(string $proxy, Activate $activate): mixed
    {
        return $this->patch("/cards/{$proxy}/activate", $activate->toArray(), null, true);
    }
}
