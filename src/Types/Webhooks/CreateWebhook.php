<?php

namespace WeDevBr\Bankly\Types\Webhooks;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Enums\Webhooks\WebhookEventNameEnum;
use WeDevBr\Bankly\Support\Contracts\CreateWebhookInterface;

class CreateWebhook implements Arrayable, CreateWebhookInterface
{
    public function __construct(
        public string $uri,
        public string $privateKey,
        public string $publicKey,
        public ?string $name,
        public ?WebhookEventNameEnum $eventName,
        public ?string $context
    ) {}

    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'privateKey' => $this->privateKey,
            'publicKey' => $this->publicKey,
            'name' => $this->name,
            'eventName' => $this->eventName->value,
            'context' => $this->context,
        ];
    }
}
