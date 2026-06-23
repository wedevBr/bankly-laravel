<?php

namespace WeDevBr\Bankly\Types\Invoice;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\InvoicePaymentInterface;

class InvoicePayment extends \stdClass implements Arrayable, InvoicePaymentInterface
{
    public string $paymentType;

    public ?array $amount;

    public ?int $paymentOptionId = null;

    public function toArray(): array
    {
        return array_filter([
            'paymentType' => $this->paymentType,
            'amount' => $this->amount,
            'paymentOptionId' => $this->paymentOptionId,
        ], fn ($value) => ! is_null($value));
    }
}
