<?php

namespace WeDevBr\Bankly\Tests\TOTP;

use InvalidArgumentException;
use WeDevBr\Bankly\Tests\TestCase;
use WeDevBr\Bankly\Types\Pix\AddressingKey;
use WeDevBr\Bankly\Types\TOTP\TOTP;

class TOTPValidatorTest extends TestCase
{
    /**
     * @return void
     *
     * @test
     */
    public function test_invalid_context()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('TOTP operation needs context');
        $totp = $this->mockedTOTP();
        $totp->context = '';
        $totp->validate();
    }

    /**
     * @return void
     *
     * @test
     */
    public function test_invalid_operation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('TOTP operation not permitted');
        $totp = $this->mockedTOTP();
        $totp->operation = 'Some invalid operation';
        $totp->validate();
    }

    /**
     * @return void
     *
     * @test
     */
    public function test_invalid_registry_entry_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid addressing key type');
        $totp = $this->mockedTOTP();
        $totp->operation = TOTP::REGISTER_ENTRY;
        $totp->data['addressingKey'] = new AddressingKey;
        $totp->validate();
    }

    /**
     * @return void
     *
     * @test
     */
    public function test_invalid_registry_entry_phone()
    {
        $addressingKey = new AddressingKey;
        $addressingKey->type = 'PHONE';
        $addressingKey->value = '+1484415162342';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value format');
        $totp = $this->mockedTOTP();
        $totp->operation = TOTP::REGISTER_ENTRY;
        $totp->data['addressingKey'] = $addressingKey;
        $totp->validate();
    }

    /**
     * @return void
     *
     * @test
     */
    public function test_convert_to_array()
    {
        $totp = $this->mockedTOTP();
        $totp = $totp->toArray();
        $this->assertIsArray($totp);
        $this->assertArrayHasKey('context', $totp);
        $this->assertArrayHasKey('operation', $totp);
        $this->assertArrayHasKey('data', $totp);
    }

    public function mockedTOTP(): TOTP
    {
        $addressingKey = new AddressingKey;
        $addressingKey->type = 'PHONE';
        $addressingKey->value = $this->faker->numerify('+55###########');

        $totp = new TOTP;
        $totp->context = 'PIX';
        $totp->documentNumber = '12345678909';
        $totp->operation = TOTP::REGISTER_ENTRY;
        $totp->data['addressingKey'] = $addressingKey;

        return $totp;
    }
}
