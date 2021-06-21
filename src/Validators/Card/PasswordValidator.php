<?php

namespace WeDevBr\Bankly\Validators\Card;

use WeDevBr\Bankly\Types\Card\Password;

/**
 * PasswordValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PasswordValidator
{
    /**
     * @var Password
     */
    private $password;

    /**
     * PasswordValidator constructor.
     * @param Password $password
     */
    public function __construct(Password $password)
    {
        $this->password = $password;
    }

    /**
     * This validate the password
     */
    public function validate(): void
    {
        $this->validatePassword();
    }

    /**
     * This validate a card password
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validatePassword()
    {
        $password = $this->password->password;
        if (empty($password) || !is_string($password) || !is_numeric($password) || strlen($password) != 4) {
            throw new \InvalidArgumentException('password should be a numeric string');
        }
    }
}
