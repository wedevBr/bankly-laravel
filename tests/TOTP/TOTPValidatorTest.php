<?php

namespace WeDevBr\Bankly\Tests\TOTP;

use WeDevBr\Bankly\Tests\TestCase;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\Types\TOTP\TOTP;

class TOTPValidatorTest extends TestCase
{
    public function testConvertToArray()
    {
        $totp = $this->validRegistryEntry();
        $totp = $totp->toArray();
        $this->assertIsArray($totp);
    }

    public function validRegistryEntry(): TOTP
    {
        $addressingKey = new AddressingKey();
        $addressingKey->type = "PHONE";
        $addressingKey->value = "+5511987654321";

        $totp = new TOTP();
        $totp->context = 'PIX';
        $totp->documentNumber = '12345678909';
        $totp->operation = TOTP::REGISTER_ENTRY;
        $totp->data['addressingKey'] = $addressingKey;

        return $totp;
    }
}