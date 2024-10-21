<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Types\Billet\CancelBillet;
use WeDevBr\Bankly\Types\Billet\DepositBillet;

class BanklyBillet extends BaseHttpClient
{
    public function __construct(?string $mtlsPassphrase = null)
    {
        parent::__construct($mtlsPassphrase);
        $this->apiVersion = '2';
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function depositBillet(DepositBillet $depositBillet): mixed
    {
        return $this->post('/bankslip', $depositBillet->toArray(), null, true);
    }

    /**
     * @throws RequestException
     */
    public function printBillet(string $authenticationCode): mixed
    {
        return $this->get("/bankslip/{$authenticationCode}/pdf");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getBillet(string $branch, string $accountNumber, string $authenticationCode): mixed
    {
        return $this->get("/bankslip/branch/{$branch}/number/{$accountNumber}/{$authenticationCode}");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getBilletByDate(string $datetime): mixed
    {
        return $this->get("/bankslip/searchstatus/{$datetime}");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getBilletByBarcode(string $barcode): mixed
    {
        return $this->get("/bankslip/{$barcode}");
    }

    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function cancelBillet(CancelBillet $cancelBillet): mixed
    {
        return $this->delete('/bankslip/cancel', $cancelBillet->toArray(), true);
    }

    /**
     * Simulate a bill settlement. Works only in sandbox
     *
     * @throws RequestException
     */
    public function billSettlementSimulate(BankAccount $bankAccount, string $txid): array
    {
        return $this->post('/bankslip/settlementpayment', [
            'authenticationCode' => $txid,
            'account' => [
                'number' => $bankAccount->account,
                'branch' => $bankAccount->branch,
            ],
        ]);
    }
}
