<?php

namespace WeDevBr\Bankly\Validators\Card;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Card\ChangeStatus;
use WeDevBr\Bankly\Types\Card\Password;

/**
 * ChangeStatusValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class ChangeStatusValidator
{
    /**
     * Card statuses.
     * No password sent:
     * - LostOrTheftCanceled
     * - CanceledByCustomer
     * - TemporarilyUserLocked
     * - Active
     */
    protected array $statuses = [
        'Building', //Cartão encontra-se em construção, não está pronto para uso;
        'Active', //Cartão encontra-se ativo para utilizar em transações;
        'ReturnedLocked', //Devolvido, ocorre quando o cartão não foi entregue no destino;
        'Sleeping', //Cartão disponível na göndola sem vínculo com a conta do cliente;
        'InTransitLocked', //Cartão sendo confeccionado ou a caminho do endereço de entrega;
        'Inactive', //Cartão comprado na gôndola sem vínculo com a conta do cliente;
        'WithoutMovementLocked', //Bloqueado por falta de uso;
        'TemporarilyUserLocked', //Bloqueado temporariamente pelo cliente;
        'WrongPasswordAttemptLocked', //Bloqueado por tentativa de senha excedida;
        'UseFraudCanceled', //Cancelado por uso em transações com fraude;
        'CredentialOnFile', //Referentes a transações de uso em serviço de recorrência;
        'RegisterFraudLocked', //Bloqueado por suspeita de fraude no cadastro;
        'UpdateRegisterLocked', //Bloqueado por pendência de atualização cadastral;
        //Non-reversible
        'UpdateRegisterCanceled', //Cancelado por pendência de atualização cadastral;
        'RegisterFraudCanceled', //Cancelado por suspeita de fraude no cadastro;
        'CanceledByCustomer', //Cancelado pelo cliente;
        'CanceledByEmitter', //Cancelado pelo emissor/parceiro;
        'ExpiredCanceled', //Cancelado por expiração;
        'StrayedCanceled', //Cancelado por extravio;
        'DeathCanceled', //Cancelado por morte;
        'LostOrTheftCanceled', //Cancelado por perda ou roubo;
        'UseFraudLocked', //Bloqueado por uso em transações com fraude;
        'CardCanceledByAccount', //Cancelado após a conta ser cancelada;
    ];

    private ChangeStatus $changeStatus;

    /**
     * ChangeStatusValidator constructor.
     */
    public function __construct(ChangeStatus $changeStatus)
    {
        $this->changeStatus = $changeStatus;
    }

    /**
     * This validate the attributes
     */
    public function validate(): void
    {
        $this->password();
        $this->status();
        $this->updateCardBinded();
    }

    public function password(): void
    {
        $password = new Password;
        $password->password = $this->changeStatus->password;
        $password->validate();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function status(): void
    {
        $status = $this->changeStatus->status;
        if (empty($status) || ! is_string($status)) {
            throw new InvalidArgumentException('status should be a string');
        }

        if (! in_array($status, $this->statuses)) {
            throw new InvalidArgumentException('this status is not valid');
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function updateCardBinded(): void
    {
        $updateCardBinded = $this->changeStatus->updateCardBinded;
        if (! is_bool($updateCardBinded)) {
            throw new InvalidArgumentException('update card binded should be a boolean');
        }
    }
}
