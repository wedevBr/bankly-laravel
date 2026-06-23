<?php

namespace WeDevBr\Bankly\Types\Invoice;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\InstallmentAdvanceInterface;

class InstallmentAdvance extends \stdClass implements Arrayable, InstallmentAdvanceInterface
{
    public int $installmentAdvanceQuantity;

    public bool $removeInterest;

    public function toArray(): array
    {
        return (array) $this;
    }
}
