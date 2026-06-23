<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CardLimitInterface;

class CardLimit extends \stdClass implements Arrayable, CardLimitInterface
{
    public string $proxy;

    public float $limit;

    public function toArray(): array
    {
        return (array) $this;
    }
}
