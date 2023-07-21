<?php

namespace WeDevBr\Bankly;

use Illuminate\Support\Facades\Facade;

class BanklyFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'bankly';
    }
}
