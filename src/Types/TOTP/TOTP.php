<?php

namespace WeDevBr\Bankly\Types\TOTP;

use WeDevBr\Bankly\Validators\TOTPValidator;

class TOTP
{
    const REGISTER_ENTRY = 'RegisterEntry';
    const PORTABILITY = 'Portability';
    const OWNERSHIP = 'Portability';
    /**
     * @var string
     */
    public string $documentNumber;

    /**
     * @var string
     */
    public string $context;

    /**
     * @var string
     */
    public string $operation;

    /**
     * @var mixed
     */
    public mixed $data;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return json_decode(json_encode($this), true);
    }

    public function validate(): void
    {
        $totpValidator = new TOTPValidator($this);
        $totpValidator->validate();
    }

}
