<?php

namespace WeDevBr\Bankly;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Types\Webhooks\CreateWebhook;

class BanklyWebhook extends BaseHttpClient
{
    /**
     * @throws RequestException
     */
    public function registerWebhook(CreateWebhook $createWebhook): ?array
    {
        return $this->post('/webhooks/configurations', $createWebhook->toArray(), asJson: true);
    }

    /**
     * @throws RequestException
     */
    public function getAllWebhooks($status, $page = 1, $pageSize = 100): ?array
    {
        $query = [
            'status' => $status,
            'page' => $page,
            'pageSize' => $pageSize,
        ];

        return $this->get('/webhooks/configurations', $query);
    }

    /**
     * @throws RequestException
     */
    public function getWebhookById(string $id): ?array
    {
        return $this->get('/webhooks/configurations/'.$id);
    }

    /**
     * @throws RequestException
     */
    public function updateWebhook(CreateWebhook $createWebhook, string $id): ?array
    {
        return $this->patch(
            '/webhooks/configurations/'.$id,
            Arr::only($createWebhook->toArray(), ['uri', 'publicKey', 'privateKey']),
            asJson: true
        );
    }

    /**
     * @throws RequestException
     */
    public function deleteWebhookById(string $id): ?array
    {
        return $this->delete('/webhooks/configurations/'.$id);
    }

    /**
     * @throws RequestException
     */
    public function disableWebhookById(string $id): ?array
    {
        return $this->patch('/webhooks/configurations/'.$id.'/disable');
    }

    /**
     * @throws RequestException
     */
    public function enableWebhookById(string $id): ?array
    {
        return $this->patch('/webhooks/configurations/'.$id.'/enable');
    }
}
