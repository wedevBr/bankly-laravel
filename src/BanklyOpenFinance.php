<?php

namespace WeDevBr\Bankly;

use Ramsey\Uuid\Uuid;
use WeDevBr\Bankly\Inputs\Ticket;
use WeDevBr\Bankly\Traits\Mtls;
use WeDevBr\Bankly\Traits\Rest;

/**
 * BanklyOpenFinance class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Judson Bandeira <judsonmelobandeira@gmail.com>
 * @copyright 2024 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BanklyOpenFinance
{
    use Mtls, Rest;

    public function createTicket(Ticket $ticket, ?string $idempotencyKey = null): array
    {
        $this->setHeaders([
            'Idempotency-Key' => $idempotencyKey ?: Uuid::uuid4()->toString(),
        ]);

        return $this->post('/openfinance/consent-flow/ticket',
            $ticket->toArray(),
            asJson: true);
    }

    public function createConsentManagement(string $accountNumber, string $documentNumber, ?string $idempotencyKey = null): array
    {
        $this->setHeaders([
            'Idempotency-Key' => $idempotencyKey ?: Uuid::uuid4()->toString(),
        ]);

        return $this->post('/openfinance/consent-management/ticket',
            compact('accountNumber', 'documentNumber'),
            asJson: true);
    }
}
