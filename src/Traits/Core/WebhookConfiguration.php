<?php
namespace WeDevBr\Bankly\Traits\Core;

/**
 * trait WebhookConfiguration
 * @see https://docs.bankly.com.br/docs/webhooks-visao-geral
 * @see https://docs.bankly.com.br/docs/convencoes-eventos
 */
trait WebhookConfiguration
{

    /**
     * Add a new webhook configuration
     *
     * @param string $name
     * @param string $eventName
     * @param string $context
     * @param string $url
     * @param string $publicKey
     * @param string $privateKey
     * @return array
     * @see https://docs.bankly.com.br/docs/webhooks-visao-geral
     */
    public function createWebhookConfiguration(string $name, string $eventName, string $context, string $url, string $publicKey, string $privateKey): array
    {
        $body = [
            'name' => $name,
            'eventName' => $eventName,
            'context' => $context,
            'url' => $url,
            'publicKey' => $publicKey,
            'privateKey' => $privateKey
        ];

        return $this->post('/webhooks/configurations', $body);
    }

    /**
     * Find a specific webhook configuration
     * @return array|mixed
     */
    public function findWebhookConfiguration(string $id)
    {
        return $this->get("/webhooks/configurations/{$id}");
    }

    /**
     * Delete a specific webhook configuration
     * @param string $id
     * @return array|mixed
     */
    public function deleteWebhookConfiguration(string $id)
    {
        return $this->delete("/webhooks/configurations/{$id}");
    }

    /**
     * Get webhooks processed messages
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $state
     * @param string|null $eventName
     * @param string|null $context
     * @param integer $page
     * @param integer $pagesize
     * @return array
     */
    public function getWebhookMessages(
        string $startDate,
        string $endDate,
        string $state = null,
        string $eventName = null,
        string $context = null,
        int $page = 1,
        int $pagesize = 100
    ): array
    {
        $query = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'state' => $state,
            'eventName' => $eventName,
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
     * @param string $idempotencyKey
     * @return null
     */
    public function reprocessWebhookMessage(string $idempotencyKey)
    {
        return $this->post('/webhooks/processed-messages/' . $idempotencyKey, [], null, true);
    }

}
