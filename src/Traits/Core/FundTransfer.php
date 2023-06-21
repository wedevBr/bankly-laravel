<?php

namespace WeDevBr\Bankly\Traits\Core;

trait FundTransfer
{
    /**
     * @param int $amount
     * @param string $description
     * @param array $sender
     * @param array $recipient
     * @param string|null $correlation_id
     * @return array|mixed
     * @throws RequestException
     */
    public function transfer(
        int $amount,
        string $description,
        array $sender,
        array $recipient,
        string $correlation_id = null
    ) {
        if ($sender['bankCode']) {
            unset($sender['bankCode']);
        }

        return $this->post(
            '/fund-transfers',
            [
                'amount' => $amount,
                'description' => $description,
                'sender' => $sender,
                'recipient' => $recipient
            ],
            $correlation_id,
            true
        );
    }

    /**
     * Get transfer funds from an account
     * @param string $branch
     * @param string $account
     * @param int $pageSize
     * @param string|null $nextPage
     * @return array|mixed
     * @throws RequestException
     */
    public function getTransferFunds(string $branch, string $account, int $pageSize = 10, string $nextPage = null)
    {
        $queryParams = [
            'branch' => $branch,
            'account' => $account,
            'pageSize' => $pageSize
        ];
        if ($nextPage) {
            $queryParams['nextPage'] = $nextPage;
        }
        return $this->get('/fund-transfers', $queryParams);
    }

    /**
     * Get Transfer Funds By Authentication Code
     * @param string $branch
     * @param string $account
     * @param string $authenticationCode
     * @return array|mixed
     * @throws RequestException
     */
    public function findTransferFundByAuthCode(string $branch, string $account, string $authenticationCode)
    {
        $queryParams = [
            'branch' => $branch,
            'account' => $account
        ];
        return $this->get('/fund-transfers/' . $authenticationCode, $queryParams);
    }

    /**
     * @param string $branch
     * @param string $account
     * @param string $authentication_id
     * @return array|mixed
     * @throws RequestException
     */
    public function getTransferStatus(string $branch, string $account, string $authentication_id)
    {
        return $this->get('/fund-transfers/' . $authentication_id . '/status', [
            'branch' => $branch,
            'account' => $account
        ]);
    }

}
