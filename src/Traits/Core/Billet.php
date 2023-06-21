<?php

namespace WeDevBr\Bankly\Traits\Core;

use WeDevBr\Bankly\Types\Billet\CancelBillet;
use WeDevBr\Bankly\Types\Billet\DepositBillet;

trait Billet
{
    /**
     * @param DepositBillet $depositBillet
     * @return array|mixed
     */
    public function depositBillet(DepositBillet $depositBillet)
    {
        return $this->post('/bankslip', $depositBillet->toArray(), null, true);
    }

    /**
     * @param string $authenticationCode
     * @return mixed
     */
    public function printBillet(string $authenticationCode)
    {
        return $this->get("/bankslip/{$authenticationCode}/pdf", null, null, false);
    }

    /**
     * @param string $branch
     * @param string $accountNumber
     * @param string $authenticationCode
     * @return array|mixed
     */
    public function getBillet(string $branch, string $accountNumber, string $authenticationCode)
    {
        return $this->get("/bankslip/branch/{$branch}/number/{$accountNumber}/{$authenticationCode}");
    }

    /**
     * @param string $datetime
     * @return array|mixed
     */
    public function getBilletByDate(string $datetime)
    {
        return $this->get("/bankslip/searchstatus/{$datetime}");
    }

    /**
     * @param string $barcode
     * @return array|mixed
     */
    public function getBilletByBarcode(string $barcode)
    {
        return $this->get("/bankslip/{$barcode}");
    }

    /**
     * @param CancelBillet $cancelBillet
     * @return array|mixed
     */
    public function cancelBillet(CancelBillet $cancelBillet)
    {
        return $this->delete('/bankslip/cancel', $cancelBillet->toArray());
    }
}
