<?php

namespace WeDevBr\Bankly\Facades;

use Illuminate\Support\Facades\Facade;

class BanklyTOTPFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'bankly_topt';
    }
}
