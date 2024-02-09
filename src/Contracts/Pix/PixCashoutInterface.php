<?php

namespace WeDevBr\Bankly\Contracts\Pix;

use Illuminate\Contracts\Support\Arrayable;

interface PixCashoutInterface extends Arrayable
{
    /**
     * This function validate the PixCashout type
     */
    public function validate(): void;
}
