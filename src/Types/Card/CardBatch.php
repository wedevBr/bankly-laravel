<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\CardBatchInterface;

class CardBatch extends \stdClass implements Arrayable, CardBatchInterface
{
    public int $quantity;

    public int $programId;

    public ?string $cardType;

    public ?string $companyKey;

    public function toArray(): array
    {
        return (array) $this;
    }
}
