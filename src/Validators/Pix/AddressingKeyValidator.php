<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\AddressingKey;

class AddressingKeyValidator
{
    /** @var AddressingKey */
    private $addressingKey;

    /**
     * @param AddressingKey $addressingKey
     */
    public function __construct(AddressingKey $addressingKey)
    {
        $this->addressingKey = $addressingKey;
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
    }

    /**
     * This validates a type of pix key
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateType()
    {
        $type = $this->addressingKey->type;
        if (empty($type) || !is_string($type)) {
            throw new \InvalidArgumentException('type should be a string');
        }

        $typeList = [
            'CPF',
            'CNPJ',
            'EMAIL',
            'PHONE',
            'EVP',
        ];
        if (!in_array($this->addressingKey->type, $typeList)) {
            throw new \InvalidArgumentException('this key type is not valid');
        }
    }

    /**
     * This validates a key value
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateValue()
    {
        $value = $this->addressingKey->value;
        if (empty($value) || !is_string($value)) {
            throw new \InvalidArgumentException('value should be a string');
        }
    }
}
