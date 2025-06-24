<?php

namespace WeDevBr\Bankly\Enums\Webhooks;

enum WebhookEventStateEnum: string
{
    case FAILED = 'Failed';
    case SUCCESS = 'Success';
}
