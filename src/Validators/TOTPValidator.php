<?php

namespace WeDevBr\Bankly\Validators;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\TOTP\TOTP;
use WeDevBr\Bankly\Validators\Pix\AddressingKeyValidator;

class TOTPValidator
{
    private TOTP $totp;

    private array $permittedTypes = ['PHONE', 'EMAIL'];

    public function __construct(TOTP $totp)
    {
        $this->totp = $totp;
        $this->validate();
    }

    public function validate(): void
    {
        $this->validateContext();
        $this->validateOperation();
    }

    private function validateContext(): void
    {
        if (! $this->totp->context) {
            throw new InvalidArgumentException('TOTP operation needs context');
        }
    }

    private function validateOperation(): void
    {
        switch ($this->totp->operation) {
            case TOTP::REGISTER_ENTRY: $this->validateAddressingKey();
                break;
            case TOTP::PORTABILITY:
            case TOTP::OWNERSHIP: $this->validateClaimId();
                break;
            default: throw new InvalidArgumentException('TOTP operation not permitted');
        }
    }

    private function validateAddressingKey(): void
    {
        if (! collect($this->totp->data)->has('addressingKey')) {
            throw new InvalidArgumentException('Data not has a addressing key to portability or ownership operation');
        }

        $addressingKey = $this->totp->data['addressingKey'];

        if (! in_array($addressingKey->type ?? '', $this->permittedTypes)) {
            throw new InvalidArgumentException('Invalid addressing key type');
        }

        if ($addressingKey->type === 'PHONE' && ! preg_match('/^\+55\d{2}\d{9}$/', $addressingKey->value)) {
            throw new InvalidArgumentException('Invalid value format');
        }

        $addressingKeyValidator = new AddressingKeyValidator($addressingKey);
        $addressingKeyValidator->validate();
    }

    private function validateClaimId(): void
    {
        if (! collect($this->totp->data)->has('claim_id')) {
            throw new InvalidArgumentException('Data not has a claim id to registry entry operation');
        }
    }
}
