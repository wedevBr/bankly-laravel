<?php

namespace WeDevBr\Bankly\Types\Customer;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\Customer\PaymentAccountValidator;

class PaymentAccount extends \stdClass implements Arrayable
{
    public string $accountType;

    /**
     * This validate and return an array
     */
    public function toArray(): array
    {
        $this->validate();

        return (array) $this;
    }

    /**
     * This function validate the payment customer data
     */
    public function validate()
    {
        $validator = new PaymentAccountValidator($this);
        $validator->validate();
    }
}
