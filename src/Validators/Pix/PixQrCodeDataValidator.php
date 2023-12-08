<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\PixQrCodeData;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

/**
 * PixQrCodeDataValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan Gonçalves <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class PixQrCodeDataValidator
{
    /** @var PixQrCodeData */
    private PixQrCodeData $pixQrCodeData;

    /**
     * @param PixQrCodeData $pixQrCodeData
     */
    public function __construct(PixQrCodeData $pixQrCodeData)
    {
        $this->pixQrCodeData = $pixQrCodeData;
    }

    /**
     * Validate the attributes of Pix qr Code Data
     *
     * @return void
     */
    public function validate(): void
    {
        $this->validateEncodedValue();
        $this->validateDocumentNumber();
    }

    /**
     * This validates the encoded string
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateEncodedValue(): void
    {
        $encodedValue = $this->pixQrCodeData->encodedValue;
        if (empty($encodedValue) || !is_string($encodedValue)) {
            throw new InvalidArgumentException('encoded value should be a string');
        }
    }

    /**
     * This validates document number
     *
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateDocumentNumber(): void
    {
        $documentNumber = $this->pixQrCodeData->documentNumber;
        if (empty($documentNumber) || !is_string($documentNumber) || !is_numeric($documentNumber)) {
            throw new InvalidArgumentException('document number should be a numeric string');
        }

        $documentValidator = new CpfCnpjValidator($documentNumber);
        $documentValidator->validate();
    }
}
