<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\AcceptanceInterface;
use WeDevBr\Bankly\Types\Billet\BankAccount;

class Acceptance extends \stdClass implements AcceptanceInterface, Arrayable
{
    public BankAccount $account;

    public Document $document;

    /**
     * This return an array
     */
    public function toArray(): array
    {
        return [
            'account' => $this->account->toArray(),
            'document' => $this->document->toArray(),
        ];
    }
}
