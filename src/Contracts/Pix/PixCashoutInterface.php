<?php

namespace WeDevBr\Bankly\Contracts\Pix;

interface PixCashoutInterface
{
    /**
     * This validate and return an array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * This function validate the PixCashout type
     *
     * @return void
     */
    public function validate(): void;
}
