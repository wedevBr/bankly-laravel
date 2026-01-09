<?php

namespace WeDevBr\Bankly\Enums\Pix\InfractionPix;

enum InfractionSituationEnum: string
{
    case Other = 'Other';
    case Scam = 'Scam';

    case AccountTakeover = 'AccountTakeover';

    case Coercion = 'Coercion';

    case FraudulentAccess = 'FraudulentAccess';

    case Unknown = 'Unknown';

}
