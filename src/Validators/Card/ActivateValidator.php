<?php

namespace WeDevBr\Bankly\Validators\Card;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Card\Activate;

/**
 * ActivateValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class ActivateValidator
{
    private Activate $activate;

    /**
     * ActivateValidator constructor.
     */
    public function __construct(Activate $activate)
    {
        $this->activate = $activate;
    }

    /**
     * This validate the activate
     */
    public function validate(): void
    {
        $this->validateActivateCode();
        $this->validatePassword();
    }

    /**
     * This validate a 12 digits card activation code
     *
     * @throws InvalidArgumentException
     */
    private function validateActivateCode(): void
    {
        $activateCode = $this->activate->activateCode;
        if (empty($activateCode) || ! is_string($activateCode) || strlen($activateCode) != 12) {
            throw new InvalidArgumentException('Invalid Activation code');
        }
    }

    /**
     * This validate a 4 digits password
     *
     * @throws InvalidArgumentException
     */
    private function validatePassword(): void
    {
        $password = $this->activate->password;
        if (empty($password) || ! is_string($password) || ! is_numeric($password) || strlen($password) != 4) {
            throw new InvalidArgumentException('password should be a numeric string');
        }
    }
}
