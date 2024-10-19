<?php

namespace WeDevBr\Bankly;

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

    public function generateTicket(Ticket $ticket)
    {
        return $this->post('/openfinance/consent-flow/ticket',
            $openFinanceTicket->toArray(),
        asJson: true);
    }
}
