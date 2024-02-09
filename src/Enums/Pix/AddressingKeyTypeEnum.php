<?php
namespace WeDevBr\Bankly\Enums\Pix;

enum AddressingKeyTypeEnum: string
{
    case CPF = "CPF";
    case CNPJ = 'CNPJ';
    case PHONE = "PHONE";
    case EMAIL = "EMAIL";


    /**
     * Get the available types
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::CPF,
            self::CNPJ,
            self::PHONE,
            self::EMAIL
        ];
    }
}
