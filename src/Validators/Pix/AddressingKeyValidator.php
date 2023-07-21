<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\AddressingKey;

class AddressingKeyValidator
{
    /** @var AddressingKey */
    private AddressingKey $addressingKey;

    /** @var bool */
    private bool $registering;

    /**
     * @param AddressingKey $addressingKey
     * @param bool $registering
     */
    public function __construct(AddressingKey $addressingKey, bool $registering = true)
    {
        $this->addressingKey = $addressingKey;
        $this->registering = $registering;
    }

    /**
     * Validate the attributes of the Addressing Key class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateType();
        $this->validateValue();
        $this->validateEvpType();
    }

    /**
     * This validates a type of pix key
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateType(): void
    {
        $type = $this->addressingKey->type;
        if (empty($type) || !is_string($type)) {
            throw new InvalidArgumentException('type should be a string');
        }

        $typeList = [
            'CPF',
            'CNPJ',
            'EMAIL',
            'PHONE',
            'EVP',
        ];
        if (!in_array($this->addressingKey->type, $typeList)) {
            throw new InvalidArgumentException('this key type is not valid');
        }
    }

    /**
     * This validates a key value
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateValue(): void
    {
        $value = $this->addressingKey->value;
        if (
            (
                !$this->registering
                || ($this->registering && $this->addressingKey->type !== 'EVP')
            )
            && (empty($value) || !is_string($value))
        ) {
            throw new InvalidArgumentException('value should be a string');
        }
    }

    /**
     * This validates a key value
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateEvpType(): void
    {
        $value = $this->addressingKey->value;
        if (
            $this->registering
            && $this->addressingKey->type === 'EVP'
            && !empty($value)
        )
        {
            throw new InvalidArgumentException('value must be empty for EVP type');
        }
    }
}
