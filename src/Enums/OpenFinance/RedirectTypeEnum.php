<?php

namespace WeDevBr\Bankly\Enums\OpenFinance;

enum RedirectTypeEnum: int
{
    case MySharedInformation = 1;
    case MyPayments = 2;
    case MyAuthorizations = 3;
}
