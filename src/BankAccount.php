<?php

/**
 * BankAccount class
 *
 * @author    jesobreira
 * @link      https://github.com/jesobreira/bankly-php/blob/master/src/BankAccount.php
 */

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Validators\BankAccountValidator;

class BankAccount extends \stdClass
{
    public $bankCode = '332';
    public $branch;
    public $account;
    public $document;
    public $name;
    public $accountType = 'CHECKING';

    /**
     * This validate and return an array
     * @return array
     */
    public function toArray(): array
    {
        $this->validate();

        return $this->toArray();
    }

    /**
     * This function validate a bank account
     */
    public function validate()
    {
        $validator = new BankAccountValidator($this);
        $validator->validate();
    }
}
