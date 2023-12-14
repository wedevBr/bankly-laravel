<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\PixDynamicQrCode;

/**
 * PixDynamicQrCodeValidator class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @copyright 2022 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixDynamicQrCodeValidator
{
    private PixDynamicQrCode $pixDynamicQrCode;

    public function __construct(PixDynamicQrCode $pixDynamicQrCode)
    {
        $this->pixDynamicQrCode = $pixDynamicQrCode;
    }

    /**
     * Validate the attributes of the PIX Dynamic Qr Code
     */
    public function validate(): void
    {
        $this->validateRecipientName();
        $this->validateAddressingKey();
        $this->validateConciliationId();
        $this->validatePayer();
        $this->validateSinglepayment();
        $this->validateChangeAmountType();
        $this->validateAmount();
        $this->validateExpiresAt();
        $this->validateAdditionalData();
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateRecipientName(): void
    {
        $recipientName = $this->pixDynamicQrCode->recipientName;
        if (empty($recipientName) || ! is_string($recipientName) || strlen($recipientName) > 25) {
            throw new InvalidArgumentException('recipient name should be a string');
        }
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateAddressingKey(): void
    {
        $this->pixDynamicQrCode->addressingKey->validate();
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateConciliationId(): void
    {
        $conciliationId = $this->pixDynamicQrCode?->conciliationId;

        if (! empty($conciliationId) && (strlen($conciliationId) < 26 || strlen($conciliationId) > 35)) {
            throw new InvalidArgumentException('conciliation id should be between 26 and 35 characters');
        }
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validatePayer(): void
    {
        $this->pixDynamicQrCode->payer->validate();
    }

    /**
     * This validates the single payment
     *
     * @throws InvalidArgumentException
     */
    private function validateSinglepayment(): void
    {
        $singlePayment = $this->pixDynamicQrCode->singlePayment;
        if (! is_bool($singlePayment)) {
            throw new InvalidArgumentException('single payment should be a boolean');
        }
    }

    /**
     * This validates the change amount type
     *
     * @throws InvalidArgumentException
     */
    private function validateChangeAmountType(): void
    {
        $types = [
            'ALLOWED',
            'NOT_ALLOWED',
        ];
        $type = $this->pixDynamicQrCode->changeAmountType;
        if (! empty($type) && ! in_array($type, $types)) {
            throw new InvalidArgumentException('change amount type is not valid');
        }
    }

    /**
     * This validates the amount
     *
     * @throws InvalidArgumentException
     */
    private function validateAmount(): void
    {
        $amount = $this->pixDynamicQrCode->amount;
        if (empty($amount) || ! is_string($amount) || ! is_numeric($amount) || $amount < 0) {
            throw new InvalidArgumentException('amount should be a numeric string');
        }
    }

    /**
     * This validates the expires at date
     *
     * @throws InvalidArgumentException
     */
    private function validateExpiresAt(): void
    {
        $expiresAt = $this->pixDynamicQrCode->expiresAt;
        try {
            if (! empty($expiresAt)) {
                $date = now()->createFromFormat('Y-m-d H:i:s', $expiresAt);
                if (! $date->gt(now('America/Sao_Paulo'))) {
                    throw new InvalidArgumentException('expires at date must be greater than the current datetime in UTC');
                }
            }
        } catch (\Throwable $th) {
            throw new InvalidArgumentException('expires at date should be a valid date');
        }
    }

    /**
     * This validates the additional data
     *
     * @throws InvalidArgumentException
     */
    private function validateAdditionalData(): void
    {
        $additionalData = $this->pixDynamicQrCode->additionalData;
        if (! empty($additionalData) && ! is_array($additionalData)) {
            throw new InvalidArgumentException('additional data should be an array');
        }
    }
}
