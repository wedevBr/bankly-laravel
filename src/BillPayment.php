<?php

namespace WeDevBr\Bankly;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\BillPaymentValidator;

/**
 * BillPayment class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class BillPayment extends \stdClass implements Arrayable
{
    public $amount;
    public $bankBranch;
    public $bankAccount;
    public $description;
    public $id;

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();
        return (array) $this;
    }

    /**
     * This function validate a bill payment
     */
    public function validate()
    {
        $validator = new BillPaymentValidator($this);
        $validator->validate();
    }
}
