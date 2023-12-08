<?php
namespace WeDevBr\Bankly\Enums\Pix;

enum CancelReasonEnum: string
{
    case CLAIMER_REQUEST = 'CLAIMER_REQUEST';
    case DONOR_REQUEST = 'DONOR_REQUEST';
    case FRAUD = 'FRAUD';

}
