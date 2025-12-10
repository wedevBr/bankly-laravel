<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use Ramsey\Uuid\Uuid;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\CustomerInterface;
use WeDevBr\Bankly\Types\Customer\PaymentAccount;

class BanklyCustomer extends BaseHttpClient
{
    /**
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getCustomerAccounts(string $documentNumber): mixed
    {
        return $this->get("/customers/{$documentNumber}/accounts");
    }

    public function createCustomerAccount(
        string $documentNumber,
        PaymentAccount $paymentAccount,
        ?string $idempotencyKey = null
    ): mixed {
        $this->setHeaders([
            'Idempotency-Key' => $idempotencyKey ?: Uuid::uuid4()->toString(),
        ]);

        return $this->post(
            "/customers/{$documentNumber}/accounts",
            $paymentAccount->toArray(),
            null,
            true
        );
    }

    /**
     * Customer register
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function customer(
        string $documentNumber,
        CustomerInterface $customer,
        ?string $correlationId = null
    ): mixed {
        return $this->put("/customers/{$documentNumber}", $customer->toArray(), $correlationId, true);
    }

    /**
     * Customer offboarding
     *
     * @param  string  $reason  HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function cancelCustomer(string $documentNumber, string $reason = 'HOLDER_REQUEST', ?string $correlationId = null): mixed
    {
        return $this->patch('/customers/'.$documentNumber.'/cancel', [
            'reason' => $reason,
        ], $correlationId, true);
    }

    /**
     * @throws RequestException
     */
    public function updateCustomer(string $documentNumber, CustomerInterface $customer, ?string $correlationId = null)
    {
        $customer = collect($customer->toArray())->only(
            [
                'email',
                'socialName',
                'phone',
                'address',
                'assertedIncome',
                'pep',
                'occupation',
                'hasBrazilianNationality',
            ]
        )->toArray();

        return $this->patch('/customers/'.$documentNumber,
            [
                'data' => array_filter($customer, fn ($value) => ! is_null($value)),
            ],
            $correlationId,
            true
        );
    }

    /**
     * Get customer
     *
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getCustomer(string $documentNumber, string $resultLevel = 'DETAILED'): mixed
    {
        return $this->get("/customers/{$documentNumber}?resultLevel={$resultLevel}");
    }
}
