<?php

namespace WeDevBr\Bankly\Validators\Pix;

use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;

/**
 * PixStaticQrCodeValidator class
 *
 * PHP version 7.3|7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan Gonçalves <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixStaticQrCodeValidator
{
    /** @var PixStaticQrCode */
    private $pixStaticQrCode;

    /**
     * @param PixStaticQrCode $pixStaticQrCode
     */
    public function __construct(PixStaticQrCode $pixStaticQrCode)
    {
        $this->pixStaticQrCode = $pixStaticQrCode;
    }

    /**
     * Validate the attributes of the PIX Static Qr Code
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateRecipientName();
        $this->validateAddressingKey();
        $this->validateAdditionalData();
        $this->validateLocation();
    }

    /**
     * This validates the recipient name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateRecipientName()
    {
        $recipientName = $this->pixStaticQrCode->recipientName;
        if (empty($recipientName) || !is_string($recipientName)) {
            throw new \InvalidArgumentException('recipient name should be a string');
        }
    }

    /**
     * This validates the recipient name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAddressingKey()
    {
        $this->pixStaticQrCode->addressingKey->validate();
    }

    /**
     * This validates the recipient name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateAdditionalData()
    {
        $additionalData = $this->pixStaticQrCode->additionalData;
        $pixKeyValue = $this->pixStaticQrCode->addressingKey->value;
        if (!empty($additionalData)) {
            $stringToValidate = $additionalData . $pixKeyValue;
            if (strlen($stringToValidate) >= 73) {
                throw new \InvalidArgumentException('additional data too large');
            }
        }
    }

    /**
     * This validates the recipient name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateLocation()
    {
        if (empty($this->pixStaticQrCode->location)) {
            throw new \InvalidArgumentException('location array is required');
        }
        $this->pixStaticQrCode->location->validate();
    }
}
