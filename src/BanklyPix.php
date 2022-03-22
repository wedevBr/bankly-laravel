<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;
use WeDevBr\Bankly\Types\Pix\PixQrCodeData;

/**
 * Class BanklyPix
 * @author Yan de Paula Gonçalves <yanw100@gmail.com>
 * @package WeDevBr\Bankly
 */
class BanklyPix
{
    use Rest;

    /**
     * Bankly constructor.
     *
     * @param null|string $mtlsCert
     * @param null|string $mtlsKey
     * @param null|string $mtlsPassphrase
     * @param null|string $apiUrl
     */
    public function __construct(
        string $mtlsCert = null,
        string $mtlsKey = null,
        string $mtlsPassphrase = null,
        string $apiUrl = null
    )
    {
        $this->mtlsCert = $mtlsCert;
        $this->mtlsKey = $mtlsKey;
        $this->mtlsPassphrase = $mtlsPassphrase;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param PixStaticQrCode $data
     * @return array
     */
    public function qrCode(PixStaticQrCode $data)
    {
        return $this->post('/baas/pix/qrcodes', $data->toArray(), null, true);
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

        return $this->post('/baas/pix/qrcodes/decode', $qrCode, null, true);
    }
}
