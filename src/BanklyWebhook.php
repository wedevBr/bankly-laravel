<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use WeDevBr\Bankly\Enums\Webhooks\WebhookEventNameEnum;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Types\Webhooks\CreateWebhook;

class BanklyWebhook extends BaseHttpClient
{
    /**
     * Get webhooks processed messages
     *
     * @throws RequestException
     * @throws RequestException
     */
    public function getWebhookMessages(
        Carbon $startDate,
        Carbon $endDate,
        ?string $state = null,
        ?WebhookEventNameEnum $eventName = null,
        ?string $context = null,
        int $page = 1,
        int $pagesize = 100
    ): array {
        $query = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'state' => $state,
            'eventName' => $eventName?->value,
            'context' => $context,
            'page' => $page,
            'pageSize' => $pagesize,
        ];

        return $this->get(
            '/webhooks/processed-messages',
            $query
        );
    }

    /**
     * Reprocess webhook message
     *
     * @throws RequestException
     */
    public function reprocessWebhookMessage(string $idempotencyKey): ?array
    {
        return $this->post('/webhooks/processed-messages/'.$idempotencyKey, [], null, true);
    }

    /**
     * @throws RequestException
     */
    public function registerWebhook(CreateWebhook $createWebhook): ?array
    {
        return $this->post('/webhooks/configurations', $createWebhook->toArray());
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
        return $this->get('/webhooks/configurations/' . $id);
    }

    /**
     * @throws RequestException
     */
    public function updateWebhook(CreateWebhook $createWebhook, string $id): ?array
    {
        return $this->patch(
            '/webhooks/configurations/' . $id,
            Arr::only($createWebhook->toArray(), ['uri', 'publicKey', 'privateKey'])
        );
    }

    /**
     * @throws RequestException
     */
    public function deleteWebhookById(string $id): ?array
    {
        return $this->delete('/webhooks/configurations/' . $id);
    }

    /**
     * @throws RequestException
     */
    public function disableWebhookById(string $id): ?array
    {
        return $this->delete('/webhooks/configurations/' . $id .  '/disable');
    }

    /**
     * @throws RequestException
     */
    public function enableWebhookById(string $id): ?array
    {
        return $this->delete('/webhooks/configurations/' . $id . '/enable');
    }
}
