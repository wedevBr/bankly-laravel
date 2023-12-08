<?php

namespace WeDevBr\Bankly;

use Illuminate\Contracts\Support\Arrayable;
use WeDevBr\Bankly\Validators\BillPaymentValidator;

/**
 * BillPayment class
 *
 * PHP 8.1|8.2|8.3
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
    public function validate(): void
    {
        $validator = new BillPaymentValidator($this);
        $validator->validate();
    }
}
