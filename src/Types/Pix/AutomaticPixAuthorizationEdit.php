<?php

namespace WeDevBr\Bankly\Types\Pix;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\AutomaticPixAuthorizationEditInterface;

class AutomaticPixAuthorizationEdit extends \stdClass implements Arrayable, AutomaticPixAuthorizationEditInterface
{
    public string $idRecurrence;

    public string $maximumValue;

    public function toArray(): array
    {
        return [
            'idRecurrence' => $this->idRecurrence,
            'amount' => [
                'maximumValue' => $this->maximumValue,
            ],
        ];
    }
}
