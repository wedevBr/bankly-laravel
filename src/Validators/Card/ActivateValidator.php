<?php

namespace WeDevBr\Bankly\Validators\Card;

use WeDevBr\Bankly\Types\Card\Activate;

/**
 * ActivateValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan de Paula <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class ActivateValidator
{
    /**
     * @var Activate
     */
    private $activate;

    /**
     * ActivateValidator constructor.
     * @param Activate $activate
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
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateActivateCode()
    {
        $activateCode = $this->activate->activateCode;
        if (empty($activateCode) || !is_string($activateCode) || strlen($activateCode) != 12) {
            throw new \InvalidArgumentException('Invalid Activation code');
        }
    }

    /**
     * This validate a 4 digits password
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validatePassword()
    {
        $password = $this->activate->password;
        if (empty($password) || !is_string($password) || !is_numeric($password) || strlen($password) != 4) {
            throw new \InvalidArgumentException('password should be a numeric string');
        }
    }
}
