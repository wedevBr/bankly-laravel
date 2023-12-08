<?php

namespace WeDevBr\Bankly\Validators\Customer;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Customer\PaymentAccount;

/**
 * PaymentAccountValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PaymentAccountValidator
{
    /**
     * @var PaymentAccount
     */
    private PaymentAccount $paymentAccount;

    /**
     * PaymentAccountValidator constructor.
     * @param PaymentAccount $paymentAccount
     */
    public function __construct(PaymentAccount $paymentAccount)
    {
        $this->paymentAccount = $paymentAccount;
    }

    /**
     * This validate the payment account
     */
    public function validate(): void
    {
        $this->validateAccountType();
        $this->validateAccountTypeValue();
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAccountType(): void
    {
        $paymentAccount = $this->paymentAccount->accountType;
        if (empty($paymentAccount) || !is_string($paymentAccount)) {
            throw new InvalidArgumentException('Invalid account type');
        }
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateAccountTypeValue(): void
    {
        $types = [
            'PAYMENT_ACCOUNT',
        ];
        if (!in_array($this->paymentAccount->accountType, $types)) {
            throw new InvalidArgumentException('this account type is not valid');
        }
    }
}
