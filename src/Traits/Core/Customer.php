<?php

namespace WeDevBr\Bankly\Traits\Core;

use WeDevBr\Bankly\Inputs\BusinessCustomer;
use WeDevBr\Bankly\Support\Contracts\CustomerInterface;
use WeDevBr\Bankly\Types\Customer\PaymentAccount;

/**
 * @see https://docs.bankly.com.br/docs/onboarding-visao-geral
 * @see https://docs.bankly.com.br/docs/registro-pessoa-fisica
 * @see https://docs.bankly.com.br/docs/offboarding-visao-geral
 */
trait Customer
{
    /**
     * Customer register
     *
     * @param string $documentNumber
     * @param CustomerInterface $customer
     * @param string|null $correlationId
     * @return array|mixed
     */
    public function customer(
        string                          $documentNumber,
        CustomerInterface $customer,
        string                          $correlationId = null
    ) {
        return $this->put("/customers/{$documentNumber}", $customer->toArray(), $correlationId, true);
    }

    /**
     * Business customer register
     *
     * @param string $documentNumber
     * @param BusinessCustomer $customer
     * @param string|null $correlationId
     * @return array|mixed
     */
    public function businessCustomer(
        string $documentNumber,
        BusinessCustomer $customer,
        string $correlationId = null
    ) {
        return $this->put("/business/{$documentNumber}", $customer->toArray(), $correlationId, true);
    }

    /**
     * Customer offboarding
     *
     * @param string $documentNumber
     * @param string|null $reason HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @param string|null $correlationId
     * @return array|mixed
     */
    public function cancelCustomer(string $documentNumber, string $reason = 'HOLDER_REQUEST', string $correlationId = null)
    {
        return $this->patch('/customers/' . $documentNumber . '/cancel', [
            'reason' => $reason
        ], $correlationId, true);
    }

    /**
     * Business offboarding
     *
     * @param string $documentNumber
     * @param string|null $reason HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @param string|null $correlationId
     * @return array|mixed
     */
    public function cancelBusiness(string $documentNumber, string $reason = 'HOLDER_REQUEST', string $correlationId = null)
    {
        return $this->patch('/business/' . $documentNumber . '/cancel', [
            'reason' => $reason
        ], $correlationId, true);
    }

    /**
     * Get customer
     *
     * @param string $documentNumber
     * @param string $resultLevel
     * @return array|mixed
     */
    public function getCustomer(string $documentNumber, string $resultLevel = 'DETAILED')
    {
        return $this->get("/customers/{$documentNumber}?resultLevel={$resultLevel}");
    }

    /**
     * Get customer
     *
     * @param string $documentNumber
     * @param string $resultLevel
     * @return array|mixed
     */
    public function getBusinessCustomer(string $documentNumber, string $resultLevel = 'DETAILED')
    {
        return $this->get("/business/{$documentNumber}?resultLevel={$resultLevel}");
    }

    /**
     * @param string $documentNumber
     * @return array|mixed
     */
    public function getCustomerAccounts(string $documentNumber)
    {
        return $this->get("/customers/{$documentNumber}/accounts");
    }

    /**
     * @param string $documentNumber
     * @return array|mixed
     */
    public function getBusinessCustomerAccounts(string $documentNumber)
    {
        return $this->get("/business/{$documentNumber}/accounts");
    }

    /**
     * @param string $documentNumber
     * @param PaymentAccount $paymentAccount
     * @return array|mixed
     */
    public function createCustomerAccount(string $documentNumber, PaymentAccount $paymentAccount)
    {
        return $this->post(
            "/customers/{$documentNumber}/accounts",
            $paymentAccount->toArray(),
            null,
            true
        );
    }

    /**
     * @param string $documentNumber
     * @param PaymentAccount $paymentAccount
     * @return array|mixed
     */
    public function createBusinessCustomerAccount(string $documentNumber, PaymentAccount $paymentAccount)
    {
        return $this->post(
            "/business/{$documentNumber}/accounts",
            $paymentAccount->toArray(),
            null,
            true
        );
    }
}
