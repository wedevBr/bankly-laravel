<?php

namespace WeDevBr\Bankly\Traits\Core;

use WeDevBr\Bankly\Contracts\Pix\PixCashoutInterface;
use WeDevBr\Bankly\Types\Pix\PixDynamicQrCode;
use WeDevBr\Bankly\Types\Pix\PixEntries;
use WeDevBr\Bankly\Types\Pix\PixQrCodeData;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;

trait Pix
{

    /**
     * Create a new PIX key link with account.
     *
     * @param PixEntries $pixEntries
     * @return array|mixed
     */
    public function registerPixKey(PixEntries $pixEntries)
    {
        return $this->post('/pix/entries', [
            'addressingKey' => $pixEntries->addressingKey->toArray(),
            'account' => $pixEntries->account->toArray(),
        ], null, true);
    }

    /**
     * Gets the list of address keys linked to an account.
     *
     * @param string $accountNumber
     * @return array|mixed
     */
    public function getPixAddressingKeys(string $accountNumber)
    {
        return $this->get("/accounts/$accountNumber/addressing-keys");
    }

    /**
     * Gets details of the account linked to an addressing key.
     *
     * @param string $documentNumber
     * @param string $addressinKeyValue
     * @return array|mixed
     */
    public function getPixAddressingKeyValue(string $documentNumber, string $addressinKeyValue)
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);
        return $this->get("/pix/entries/$addressinKeyValue");
    }

    /**
     * Delete a key link with account.
     *
     * @param string $addressingKeyValue
     * @return array|mixed
     */
    public function deletePixAddressingKeyValue(string $addressingKeyValue)
    {
        return $this->delete("/pix/entries/$addressingKeyValue");
    }

    /**
     * @param PixCashoutInterface $pixCashout
     * @param string $correlationId
     * @return array|mixed
     */
    public function pixCashout(PixCashoutInterface $pixCashout, string $correlationId)
    {
        return $this->post('/pix/cash-out', $pixCashout->toArray(), $correlationId, true);
    }

    /**
     * @param PixCashoutInterface $pixCashout
     * @return array|mixed
     */
    public function pixRefund(PixCashoutInterface $pixRefund)
    {
        return $this->post('/pix/cash-out:refund', $pixRefund->toArray(), null, true);
    }

    /**
     * @param string $documentNumber
     * @param PixStaticQrCode $data
     * @return array
     */
    public function qrCode(string $documentNumber, PixStaticQrCode $data)
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);
        return $this->post('/pix/qrcodes/static/transfer', $data->toArray(), null, true);
    }

    /**
     * @param string $documentNumber
     * @param PixDynamicQrCode $data
     * @return array
     */
    public function dynamicQrCode(string $documentNumber, PixDynamicQrCode $data)
    {
        $this->setHeaders(['x-bkly-pix-user-id' => $documentNumber]);
        return $this->post('/pix/qrcodes/dynamic/payment', $data->toArray(), null, true);
    }

    /**
     * @param PixQrCodeData $data
     * @return array
     */
    public function qrCodeDecode(PixQrCodeData $data)
    {
        $qrCode = $data->toArray();

        $this->setHeaders([
            'x-bkly-pix-user-id' => $qrCode['documentNumber'],
        ]);

        return $this->post('/pix/qrcodes/decode', $qrCode, null, true);
    }
}
