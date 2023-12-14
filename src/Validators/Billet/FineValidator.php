<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\Fine;

/**
 * FineValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class FineValidator
{
    private Fine $fine;

    public function __construct(Fine $fine)
    {
        $this->fine = $fine;
    }

    /**
     * Validate the attributes of the fine class
     */
    public function validate(): void
    {
        $this->validateStartDate();
        $this->validateValue();
        $this->validateType();
    }

    /**
     * This validates the start date
     *
     * @throws InvalidArgumentException
     */
    private function validateStartDate(): void
    {
        $startDate = $this->fine->startDate;
        try {
            $date = now()->createFromFormat('Y-m-d', $startDate);
            if (! $date->gt(now())) {
                throw new InvalidArgumentException('start date must be greater than the current date');
            }
        } catch (\Throwable $th) {
            throw new InvalidArgumentException('start date should be a valid date');
        }
    }

    /**
     * This validates the value
     *
     * @throws InvalidArgumentException
     */
    private function validateValue(): void
    {
        $value = $this->fine->value;
        if (empty($value) || ! is_string($value) || ! is_numeric($value) || $value <= 0) {
            throw new InvalidArgumentException('value should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates a type
     *
     * @throws InvalidArgumentException
     */
    private function validateType(): void
    {
        $type = $this->fine->type;
        if (empty($type) || ! is_string($type)) {
            throw new InvalidArgumentException('type should be a string');
        }

        $types = [
            'FixedAmount',
            'Percent',
            'Free',
        ];
        if (! in_array($type, $types)) {
            throw new InvalidArgumentException('this type is not valid');
        }
    }
}
