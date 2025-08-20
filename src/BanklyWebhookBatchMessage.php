<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use WeDevBr\Bankly\Enums\Webhooks\WebhookContextNameEnum;
use WeDevBr\Bankly\Enums\Webhooks\WebhookEventNameEnum;
use WeDevBr\Bankly\Enums\Webhooks\WebhookEventStateEnum;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;

class BanklyWebhookBatchMessage extends BaseHttpClient
{
    /**
     * @throws RequestException
     * @throws \Throwable
     */
    public function getBatchMessages(
        Carbon $startDate,
        Carbon $endDate,
        int $page = 1,
        int $pageSize = 100
    ): array {
        throw_if($pageSize > 100, \InvalidArgumentException::class, 'Page size must be less than 100');
        throw_if($page < 1, \InvalidArgumentException::class, 'Page must be greater than 0');
        throw_if($startDate->gt($endDate), \InvalidArgumentException::class, 'Start date must be less than end date');
        $query = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'page' => $page,
            'pageSize' => $pageSize,
        ];

        return $this->get('/webhooks/processed-messages/batch', array_filter($query));
    }

    /**
     * @param  Collection<WebhookEventNameEnum>|null  $eventNames
     *
     * @throws \Throwable
     */
    public function reprocessBatchMessage(
        Carbon $startDate,
        Carbon $endDate,
        ?WebhookContextNameEnum $context = null,
        ?Collection $eventNames = null,
        ?WebhookEventStateEnum $eventState = null,
    ): ?array {
        throw_if($startDate->gt($endDate), \InvalidArgumentException::class, 'Start date must be less than end date');
        throw_if(
            is_null($context) && $endDate->diffInDays($startDate) > 30,
            \InvalidArgumentException::class,
            'Context is required when date range is bigger than 30 days'
        );
        throw_if(
            ! is_null($eventNames) && $eventNames->contains(fn ($item) => ! $item instanceof WebhookEventNameEnum),
            \InvalidArgumentException::class,
            'Event names must be an instance of WebhookEventNameEnum'
        );

        $body = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'context' => $context?->value,
            'eventNames' => $eventNames instanceof Collection ? $eventNames->map(fn ($item) => $item->value)->toArray() : null,
            'eventState' => $eventState?->value,
        ];

        return $this->post('/webhooks/processed-messages/batch', array_filter($body), asJson: true);
    }
}
