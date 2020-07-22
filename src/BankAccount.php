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

    public function toArray(): array
    {
        $this->validate();

        return $this->toArray();
    }

    public function validate()
    {
        $validator = new BankAccountValidator($this);
        $validator->validate();
    }
}
