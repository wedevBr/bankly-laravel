<?php

namespace WeDevBr\Bankly\Validators\Billet;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Billet\BankAccount;
use WeDevBr\Bankly\Types\Billet\CancelBillet;

/**
 * CancelBilletValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Marco Belmont <marco.santos@wedev.software>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class CancelBilletValidator
{
    /** @var CancelBillet */
    private CancelBillet $cancelBillet;

    /**
     * @param CancelBillet $cancelBillet
     */
    public function __construct(CancelBillet $cancelBillet)
    {
        $this->cancelBillet = $cancelBillet;
    }

    /**
     * Validate the attributes of the deposit billet class
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateBankAccount();
        $this->validateAuthenticationCode();
    }

    /**
     * This validates a bank account
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateBankAccount(): void
    {
        if (!$this->cancelBillet->account instanceof BankAccount) {
            throw new InvalidArgumentException('account should be a BankAccount type');
        }

        $this->cancelBillet
            ->account
            ->validate();
    }

    /**
     * This validates authentication code
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAuthenticationCode(): void
    {
        $authenticationCode = $this->cancelBillet->authenticationCode;
        if (empty($authenticationCode) || !is_string($authenticationCode)) {
            throw new InvalidArgumentException('authentication code should be a string');
        }
    }
}
