<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Inputs\Acceptance;
use WeDevBr\Bankly\Traits\Mtls;
use WeDevBr\Bankly\Traits\Rest;

/**
 * BanklyLegalAgreement class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Judson Bandeira <judsonmelobandeira@gmail.com>
 * @copyright 2024 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BanklyLegalAgreement
{
    use Mtls, Rest;

    public function getLegalAgreementDocument(
        string $type = 'TERMS_AND_CONDITIONS_OF_USE'
    )
    {
        return $this->get('/legal-agreements/file', [
            'type' => $type,
        ]);
    }

    public function acceptLegalAgreement(Acceptance $acceptance)
    {
        return $this->post('/legal-agreements/accept', [
            'document' => $acceptance->document->value,
            'account' => [
                'number' => $acceptance->account->number,
                'branch' => $acceptance->account->branch,
            ],
        ]);
    }
}
