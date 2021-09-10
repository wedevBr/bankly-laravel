<?php

namespace WeDevBr\Bankly;

use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Traits\Rest;
use WeDevBr\Bankly\Types\Pix\PixStaticQrCode;

/**
 * Class BanklyPix
 * @author Yan de Paula Gonçalves <yanw100@gmail.com>
 * @package WeDevBr\Bankly
 */
class BanklyPix
{
    use Rest;

    /**
     * @param string $clientSecret
     * @param string $clientId
     */
    public function __construct($clientSecret = null, $clientId = null)
    {
        Auth::login()
            ->setClientId($clientId)
            ->setClientId($clientSecret);
    }

    public function qrCode(PixStaticQrCode $data)
    {
        return $this->post('/baas/pix/qrcodes', $data->toArray());
    }
}