<?php

namespace WeDevBr\Bankly\Events;

class BanklyAuthenticatedEvent
{
    /**
     * @var string
     */
    public string $token;

    /**
     * @var string
     */
    public string $tokenExpiry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $token, string $tokenExpiry)
    {
        $this->token = $token;
        $this->tokenExpiry = $tokenExpiry;
    }
}
