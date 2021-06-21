<?php

namespace WeDevBr\Bankly\Validators\Card;

use WeDevBr\Bankly\Types\Card\Password;
use WeDevBr\Bankly\Types\Card\Wallet;

/**
 * WalletValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class WalletValidator
{
    /** @var array */
    protected $wallets = [
        'GooglePay',
        'ApplePay',
    ];

    /** @var array */
    protected $brands = [
        'Mastercard',
        'Visa',
    ];

    /**
     * @var Wallet
     */
    private $wallet;

    /**
     * WalletValidator constructor.
     * @param Wallet $wallet
     */
    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * This validate the wallet data
     */
    public function validate(): void
    {
        $this->proxy();
        $this->wallet();
        $this->brand();
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    public function proxy()
    {
        $proxy = $this->wallet->proxy;
        if (empty($proxy) || !is_string($proxy)) {
            throw new \InvalidArgumentException('proxy should be a string');
        }
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    public function wallet()
    {
        $wallet = $this->wallet->wallet;
        if (empty($wallet) || !is_string($wallet)) {
            throw new \InvalidArgumentException('wallet should be a string');
        }

        if (!in_array($wallet, $this->wallets)) {
            throw new \InvalidArgumentException('this wallet is not valid');
        }
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     */
    public function brand()
    {
        $brand = $this->wallet->brand;
        if (empty($brand) || !is_string($brand)) {
            throw new \InvalidArgumentException('brand should be a string');
        }

        if (!in_array($brand, $this->brands)) {
            throw new \InvalidArgumentException('this brand is not valid');
        }
    }
}
