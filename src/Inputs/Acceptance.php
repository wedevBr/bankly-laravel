<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Types\Billet\BankAccount;

class Acceptance extends \stdClass implements Arrayable
{
    public BankAccount $account;
    public Document $document;

    /**
     * This return an array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
