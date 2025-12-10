<?php

namespace WeDevBr\Bankly\Enums\Pix\ScheduledPix;

enum LiquidationCodeSubTypeEnum: string
{
    case COMMON_SCHEDULING = 'COMMON_SCHEDULING';
    case AUTOMATIC_PIX_SCHEDULING = 'AUTOMATIC_PIX_SCHEDULING';
}
