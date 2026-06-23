<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\InstallmentAdvanceInterface;
use WeDevBr\Bankly\Support\Contracts\InstallmentSimulationInterface;
use WeDevBr\Bankly\Support\Contracts\InvoicePaymentInterface;

class BanklyInvoice extends BaseHttpClient
{
    public function getOpenInvoice(string $documentNumber, string $proxy): mixed
    {
        return $this->get("/cards/invoices/document/{$documentNumber}/proxy/{$proxy}/open");
    }

    public function getInvoiceById(string $statementId): mixed
    {
        return $this->get("/cards/invoices/{$statementId}");
    }

    public function getInvoicesByPeriod(string $documentNumber, string $proxy, Carbon $initialDate, Carbon $finalDate): mixed
    {
        $query = [
            'initialDate' => $initialDate->format('Y-m-d'),
            'finalDate' => $finalDate->format('Y-m-d'),
        ];

        return $this->get("/cards/invoices/document/{$documentNumber}/proxy/{$proxy}", $query);
    }

    public function getCreditLimit(string $documentNumber, string $proxy): mixed
    {
        return $this->get("/cards/invoices/document/{$documentNumber}/proxy/{$proxy}/limits");
    }

    public function getPaymentOptions(string $statementId): mixed
    {
        return $this->get("/cards/invoices/{$statementId}/payment-options");
    }

    public function generatePayment(string $statementId, InvoicePaymentInterface $payment, ?string $correlationId = null): mixed
    {
        return $this->post("/cards/invoices/{$statementId}/payment", $payment->toArray(), $correlationId, true);
    }

    public function simulateInstallments(string $statementId, InstallmentSimulationInterface $simulation, ?string $correlationId = null): mixed
    {
        return $this->post("/cards/invoices/{$statementId}/installments", $simulation->toArray(), $correlationId, true);
    }

    public function simulateInstallmentAdvance(string $statementId, string $statementItemId): mixed
    {
        return $this->get("/cards/invoices/{$statementId}/item/{$statementItemId}/installment-advance");
    }

    public function confirmInstallmentAdvance(string $statementId, string $statementItemId, InstallmentAdvanceInterface $advance, ?string $correlationId = null): mixed
    {
        return $this->post("/cards/invoices/{$statementId}/item/{$statementItemId}/advancement", $advance->toArray(), $correlationId, true);
    }
}
