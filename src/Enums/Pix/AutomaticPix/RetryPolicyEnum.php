<?php

namespace WeDevBr\Bankly\Enums\Pix\AutomaticPix;

enum RetryPolicyEnum: string
{
    case ALLOW_3R_7D = 'ALLOW_3R_7D';
    case DOES_NOT_ALLOW = 'DOES_NOT_ALLOW';
}
