<?php

namespace WeDevBr\Bankly\Tests;

use InvalidArgumentException;
use WeDevBr\Bankly\Validators\CpfCnpjValidator;

class CpfCnpjValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function test_valid_numeric_cnpj_legacy(): void
    {
        $validator = new CpfCnpjValidator('71569042000196');
        $this->assertTrue($validator->validate());
    }

    /**
     * @test
     */
    public function test_valid_numeric_cnpj_with_mask(): void
    {
        $validator = new CpfCnpjValidator('71.569.042/0001-96');
        $this->assertTrue($validator->validate());
    }

    /**
     * @test
     */
    public function test_valid_alphanumeric_cnpj(): void
    {
        // CNPJ alfanumérico oficial: 12.ABC.345/01DE-35 (DV=35)
        $validator = new CpfCnpjValidator('12ABC34501DE35');
        $this->assertTrue($validator->validate());
    }

    /**
     * @test
     */
    public function test_valid_alphanumeric_cnpj_with_mask(): void
    {
        $validator = new CpfCnpjValidator('12.ABC.345/01DE-35');
        $this->assertTrue($validator->validate());
    }

    /**
     * @test
     */
    public function test_valid_alphanumeric_cnpj_lowercase(): void
    {
        // Deve normalizar para maiúsculas antes de validar
        $validator = new CpfCnpjValidator('12abc34501de35');
        $this->assertTrue($validator->validate());
    }

    /**
     * @test
     */
    public function test_invalid_alphanumeric_cnpj_wrong_dv(): void
    {
        $validator = new CpfCnpjValidator('12ABC34501DE99');
        $this->assertFalse($validator->validate());
    }

    /**
     * @test
     */
    public function test_invalid_cnpj_with_alphabetic_dv(): void
    {
        // Os dígitos verificadores devem ser sempre numéricos
        $validator = new CpfCnpjValidator('12ABC34501DEAB');
        $this->assertFalse($validator->validate());
    }

    /**
     * @test
     */
    public function test_invalid_cnpj_all_same_letter(): void
    {
        $validator = new CpfCnpjValidator('AAAAAAAAAAAAAA');
        $this->assertFalse($validator->validate());
    }

    /**
     * @test
     */
    public function test_invalid_cnpj_all_same_digit(): void
    {
        $validator = new CpfCnpjValidator('00000000000000');
        $this->assertFalse($validator->validate());
    }

    /**
     * @test
     */
    public function test_valid_cpf(): void
    {
        $validator = new CpfCnpjValidator('01234567890');
        $this->assertTrue($validator->validate());
    }

    /**
     * @test
     */
    public function test_invalid_cpf_with_letters(): void
    {
        // CPF não pode conter letras
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cpf_cnpj invalid');
        $validator = new CpfCnpjValidator('01234A67890');
        $validator->validate();
    }

    /**
     * @test
     */
    public function test_invalid_length_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cpf_cnpj invalid');
        $validator = new CpfCnpjValidator('123456789');
        $validator->validate();
    }

    /**
     * @test
     */
    public function test_format_numeric_cnpj(): void
    {
        $validator = new CpfCnpjValidator('71569042000196');
        $this->assertEquals('71.569.042/0001-96', $validator->format());
    }

    /**
     * @test
     */
    public function test_format_alphanumeric_cnpj(): void
    {
        $validator = new CpfCnpjValidator('12ABC34501DE35');
        $this->assertEquals('12.ABC.345/01DE-35', $validator->format());
    }

    /**
     * @test
     */
    public function test_format_cpf(): void
    {
        $validator = new CpfCnpjValidator('01234567890');
        $this->assertEquals('012.345.678-90', $validator->format());
    }

    /**
     * @test
     */
    public function test_char_to_value_digits(): void
    {
        $validator = new CpfCnpjValidator('0');
        $reflection = new \ReflectionMethod(CpfCnpjValidator::class, 'charToValue');
        $reflection->setAccessible(true);

        $this->assertSame(0, $reflection->invoke($validator, '0'));
        $this->assertSame(9, $reflection->invoke($validator, '9'));
    }

    /**
     * @test
     */
    public function test_char_to_value_letters(): void
    {
        $validator = new CpfCnpjValidator('0');
        $reflection = new \ReflectionMethod(CpfCnpjValidator::class, 'charToValue');
        $reflection->setAccessible(true);

        // ASCII - 48: A(65)=17, B(66)=18, Z(90)=42
        $this->assertSame(17, $reflection->invoke($validator, 'A'));
        $this->assertSame(18, $reflection->invoke($validator, 'B'));
        $this->assertSame(42, $reflection->invoke($validator, 'Z'));
    }
}
