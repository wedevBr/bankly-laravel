<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CardDueDateInterface;

class CardDueDate extends \stdClass implements Arrayable, CardDueDateInterface
{
    public int $dueDate;

    public ?int $expirationDate;

    public function toArray(): array
    {
        return (array) $this;
    }
}
