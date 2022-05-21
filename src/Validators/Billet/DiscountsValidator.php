<?php

namespace WeDevBr\Bankly\Validators\Billet;

use WeDevBr\Bankly\Types\Billet\Discounts;

/**
 * DiscountsValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class DiscountsValidator
{
    /** @var Discounts */
    private $discounts;

    /**
     * @param Discounts $discounts
     */
    public function __construct(Discounts $discounts)
    {
        $this->discounts = $discounts;
    }

    /**
     * Validate the attributes of the discounts class
     *
     * @return void
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
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateLimitDate()
    {
        $limitDate = $this->discounts->limitDate;
        try {
            $date = now()->createFromFormat('Y-m-d', $limitDate);
            if (!$date->gt(now())) {
                throw new \InvalidArgumentException('limit date must be greater than the current date');
            }
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException('limit date should be a valid date');
        }
    }

    /**
     * This validates the value
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateValue()
    {
        $value = $this->discounts->value;
        if (empty($value) || !is_string($value) || !is_numeric($value) || $value <= 0) {
            throw new \InvalidArgumentException('value should be a numeric string and greater than zero');
        }
    }

    /**
     * This validates a type
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateType()
    {
        $type = $this->discounts->type;
        if (empty($type) || !is_string($type)) {
            throw new \InvalidArgumentException('type should be a string');
        }

        $types = [
            'FixedAmountUntilLimitDate',
            'FixedPercentUntilLimitDate',
            'Free'
        ];
        if (!in_array($type, $types)) {
            throw new \InvalidArgumentException('this type is not valid');
        }
    }
}
