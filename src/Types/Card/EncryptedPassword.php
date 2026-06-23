<?php

namespace WeDevBr\Bankly\Types\Card;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Support\Contracts\EncryptedPasswordInterface;

class EncryptedPassword extends \stdClass implements Arrayable, EncryptedPasswordInterface
{
    public string $encryptedPassword;

    public ?string $publicKey;

    public function toArray(): array
    {
        return (array) $this;
    }
}
