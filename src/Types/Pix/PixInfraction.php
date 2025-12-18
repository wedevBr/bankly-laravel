<?php

namespace WeDevBr\Bankly\Types\Pix;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Enums\Pix\InfractionPix\InfractionSituationEnum;
use WeDevBr\Bankly\Support\Contracts\PixInfractionInterface;

class PixInfraction implements Arrayable, PixInfractionInterface
{
    public string $endToEndId;
    public Carbon $requestDate;
    public string $branch;
    public string $account;
    public InfractionSituationEnum $situation;
    public ?string $description;


    public function toArray(): array
    {
        return array_filter([
            'endToEndId' => $this->endToEndId,
            'description' => $this->description,
            'requestDate' => $this->requestDate->toIso8601String(),
            'situation' => $this->situation->value,
        ]);
    }
}
