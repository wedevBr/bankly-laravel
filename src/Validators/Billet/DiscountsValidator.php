<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\Discounts;

/**
 * DiscountsValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class DiscountsValidator
{
    private Discounts $discounts;

    public function __construct(Discounts $discounts)
    {
        $this->discounts = $discounts;
    }

    /**
     * Validate the attributes of the discounts class
     */
    public function validate(): void
    {
        $this->validateLimitDate();
        $this->validateValue();
        $this->validateType();
    }

    /**
     * This validates the limit date
     *
     * @throws InvalidArgumentException
     */
    private function validateLimitDate(): void
    {
        $limitDate = $this->discounts->limitDate;
        try {
            $date = now()->createFromFormat('Y-m-d', $limitDate);
            if (! $date->gt(now())) {
                throw new InvalidArgumentException('limit date must be greater than the current date');
            }
        } catch (\Throwable $th) {
            throw new InvalidArgumentException('limit date should be a valid date');
        }
    }

    /**
     * This validates the value
     *
     * @throws InvalidArgumentException
     */
    private function validateValue(): void
    {
        $value = $this->discounts->value;
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
        $type = $this->discounts->type;
        if (empty($type) || ! is_string($type)) {
            throw new InvalidArgumentException('type should be a string');
        }

        $types = [
            'FixedAmount',
            'Percent',
        ];

        if (! in_array($type, $types)) {
            throw new InvalidArgumentException('this type is not valid');
        }
    }
}
