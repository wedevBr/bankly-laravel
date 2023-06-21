<?php

namespace WeDevBr\Bankly\Core;

namespace WeDevBr\Bankly\Traits\Core;

trait Payment
{
    /**
     * Validate of boleto or dealership
     *
     * @param string $code - Digitable line
     * @param string $correlationId
     * @return array|mixed
     * @throws RequestException
     */
    public function paymentValidate(string $code, string $correlationId)
    {
        return $this->post('/bill-payment/validate', ['code' => $code], $correlationId, true);
    }

    /**
     * Confirmation of payment of boleto or dealership
     *
     * @param BillPayment $billPayment
     * @param string $correlationId
     * @return array|mixed
     */
    public function paymentConfirm(
        BillPayment $billPayment,
        string $correlationId
    ) {
        return $this->post('/bill-payment/confirm', $billPayment->toArray(), $correlationId, true);
    }
}
