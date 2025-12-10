<?php

namespace WeDevBr\Bankly\Enums\Pix\AutomaticPix;

enum StatusEnum: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case CANCELED = 'CANCELED';
    case EXPIRED = 'EXPIRED';
    case REJECTED = 'REJECTED';
    case ERROR = 'ERROR';
    case COMPLETED = 'COMPLETED';
}
