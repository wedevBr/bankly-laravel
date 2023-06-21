<?php

namespace WeDevBr\Bankly\Traits\Core;

/**
 * @see https://docs.bankly.com.br/docs/gestao-de-conta-visao-geral
 */
trait Account
{
    /**
     * Retrieve your balance account
     * @param string $branch
     * @param string $account
     * @return array|mixed
     * @note If you have a RequestException on this endpoint in staging environment, please use getAccount() method instead.
     */
    public function getBalance(string $branch, string $account)
    {
        return $this->get('/account/balance', [
            'branch' => $branch,
            'account' => $account
        ]);
    }

    /**
     * @param string $account
     * @param string $includeBalance
     * @return array|mixed
     * @note This method on this date (2020-10-21) works only on staging environment. Contact Bankly/Acesso for more details
     */
    public function getAccount(string $account, string $includeBalance = 'true')
    {
        return $this->get('/accounts/' . $account, [
            'includeBalance' => $includeBalance,
        ]);
    }

    /**
     * Returns the income report for a given year
     *
     * @param string $account
     * @param string|null $year If not informed, the previous year will be used
     * @return array
     */
    public function getIncomeReport(string $account, string $year = null): array
    {
        return $this->get('/accounts/' . $account . '/income-report', [
            'calendar' => $year
        ]);
    }

    /**
     * Returns the PDF of the income report for a given year in base64 format
     *
     * @param string $account
     * @param string|null $year If not informed, the previous year will be used
     * @return mixed
     */
    public function getIncomeReportPrint(string $account, string $year = null)
    {
        return $this->get('/accounts/' . $account . '/income-report/print', [
            'calendar' => $year
        ]);
    }

    /**
     * @param string $branch
     * @param string $account
     * @param int $page
     * @param int $pagesize
     * @param string $include_details
     * @param string[] $cardProxy
     * @param string|null $begin_date
     * @param string|null $end_date
     * @return array
     */
    public function getEvents(
        string $branch,
        string $account,
        int $page = 1,
        int $pagesize = 20,
        string $include_details = 'true',
        array $cardProxy = [],
        string $begin_date = null,
        string $end_date = null
    ): array
    {
        $query = [
            'branch' => $branch,
            'account' => $account,
            'page' => $page,
            'pageSize' => $pagesize,
            'includeDetails' => $include_details
        ];

        if (!empty($cardProxy)) {
            $query['cardProxy'] = $cardProxy;
        }

        if ($begin_date) {
            $query['beginDateTime'] = $begin_date;
        }

        if ($end_date) {
            $query['endDateTime'] = $end_date;
        }

        return $this->get(
            '/events',
            $query
        );
    }

    /**
     * Close account
     *
     * @param string $account
     * @param string|null $reason HOLDER_REQUEST|COMMERCIAL_DISAGREEMENT
     * @param string|null $correlationId
     * @return array|mixed
     */
    public function closeAccount(string $account, string $reason = 'HOLDER_REQUEST', string $correlationId = null)
    {
        return $this->patch('/accounts/' . $account . '/closure', [
            'reason' => $reason
        ], $correlationId, true);
    }
}
