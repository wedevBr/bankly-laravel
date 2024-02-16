<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\Traits\Mtls;
use WeDevBr\Bankly\Traits\Rest;

class BanklyIncomeReport
{
    use Mtls;
    use Rest;

    public function __construct(?string $mtlsPassphrase = null)
    {
        $this->mtlsCert = config('bankly')['mtls_cert_path'] ?? null;
        $this->mtlsKey = config('bankly')['mtls_key_path'] ?? null;
        $this->mtlsPassphrase = $mtlsPassphrase ?? config('bankly')['mtls_passphrase'];
        $this->apiVersion = '2';
    }

    /**
     * Returns the income report for a given year
     *
     * @param  string|null  $year If not informed, the previous year will be used
     * @return array|mixed
     *
     * @throws RequestException
     */
    public function getIncomeReport(string $account, ?string $year = null): mixed
    {
        return $this->get('/accounts/'.$account.'/income-report', array_filter([
            'calendar' => $year,
        ]));
    }
}
