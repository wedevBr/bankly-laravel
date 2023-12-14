<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;

/**
 * PixStaticQrCodeValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan Gonçalves <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixStaticQrCodeValidator
{
    private PixStaticQrCode $pixStaticQrCode;

    public function __construct(PixStaticQrCode $pixStaticQrCode)
    {
        $this->pixStaticQrCode = $pixStaticQrCode;
    }

    /**
     * Validate the attributes of the PIX Static Qr Code
     */
    public function validate(): void
    {
        $this->validateRecipientName();
        $this->validateAddressingKey();
        $this->validateAdditionalData();
        $this->validateLocation();
        $this->validateConciliationId();
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateRecipientName(): void
    {
        $recipientName = $this->pixStaticQrCode->recipientName;
        if (empty($recipientName) || ! is_string($recipientName)) {
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
        $this->pixStaticQrCode->addressingKey->validate();
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateAdditionalData(): void
    {
        $additionalData = $this->pixStaticQrCode->additionalData;
        $pixKeyValue = $this->pixStaticQrCode->addressingKey->value;
        if (! empty($additionalData)) {
            $stringToValidate = $additionalData.$pixKeyValue;
            if (strlen($stringToValidate) >= 73) {
                throw new InvalidArgumentException('additional data too large');
            }
        }
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateLocation(): void
    {
        if (empty($this->pixStaticQrCode->location)) {
            throw new InvalidArgumentException('location array is required');
        }
        $this->pixStaticQrCode->location->validate();
    }

    /**
     * This validates the recipient name
     *
     * @throws InvalidArgumentException
     */
    private function validateConciliationId(): void
    {
        $conciliationId = $this->pixStaticQrCode->conciliationId;
        if (! empty($conciliationId) && strlen($conciliationId) > 25) {
            throw new InvalidArgumentException('conciliation id should be until 25 characters');
        }
    }
}
