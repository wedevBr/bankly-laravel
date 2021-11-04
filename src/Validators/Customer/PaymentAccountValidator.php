<?php

namespace WeDevBr\Bankly\Validators\Customer;

use WeDevBr\Bankly\Types\Customer\PaymentAccount;

/**
 * PaymentAccountValidator class
 *
 * PHP version 7.3|7.4|8.0
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
    private $paymentAccount;

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
     * @throws \InvalidArgumentException
     */
    private function validateAccountType()
    {
        $paymentAccount = $this->paymentAccount->accountType;
        if (empty($paymentAccount) || !is_string($paymentAccount)) {
            throw new \InvalidArgumentException('Invalid account type');
        }
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAccountTypeValue()
    {
        $types = [
            'PAYMENT_ACCOUNT',
        ];
        if (!in_array($this->paymentAccount->accountType, $types)) {
            throw new \InvalidArgumentException('this account type is not valid');
        }
    }
}
