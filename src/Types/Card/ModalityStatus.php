<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\ModalityStatusInterface;

class ModalityStatus extends \stdClass implements Arrayable, ModalityStatusInterface
{
    public string $functionality;

    public string $status;

    public function toArray(): array
    {
        return (array) $this;
    }
}
