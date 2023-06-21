<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Traits\Core\Account;
use WeDevBr\Bankly\Traits\Core\AccountLimit;
use WeDevBr\Bankly\Traits\Core\Billet;
use WeDevBr\Bankly\Traits\Core\Card;
use WeDevBr\Bankly\Traits\Core\Customer;
use WeDevBr\Bankly\Traits\Core\Document;
use WeDevBr\Bankly\Traits\Core\FundTransfer;
use WeDevBr\Bankly\Traits\Core\Miscellaneous;
use WeDevBr\Bankly\Traits\Core\Payment;
use WeDevBr\Bankly\Traits\Core\Pix;
use WeDevBr\Bankly\Traits\Core\WebhookConfiguration;
use WeDevBr\Bankly\Traits\Rest;

/**
 * Class Bankly
 * @author Adeildo Amorim <adeildo@wedev.software>
 * @package WeDevBr\Bankly
 */
class Bankly
{
    use Account;
    use AccountLimit;
    use Billet;
    use Card;
    use Customer;
    use Document;
    use FundTransfer;
    use Miscellaneous;
    use Payment;
    use Pix;
    use Rest;
    use WebhookConfiguration;
}
