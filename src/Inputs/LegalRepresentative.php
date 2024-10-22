<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Business LegalRepresentative class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Gabriel Oliveira <gabriel.oliveira@wedev.software>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class LegalRepresentative implements Arrayable
{
    protected string $documentNumber;

    protected string $registerName;

    protected ?string $socialName = null;

    protected CustomerPhone $phone;

    protected CustomerAddress $address;

    protected string $birthDate;

    protected string $motherName;

    protected string $email;

    protected string $pepLevel;

    protected string $declaredIncome;

    protected string $selfieToken;

    protected string $idCardFrontToken;

    protected string $idCardBackToken;

    protected string $occupation;

    public function setDocumentNumber(string $documentNumber): LegalRepresentative
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function setRegisterName(string $name): LegalRepresentative
    {
        $this->registerName = $name;

        return $this;
    }

    public function setSocialName(string $name): LegalRepresentative
    {
        $this->socialName = $name;

        return $this;
    }

    public function setPhone(CustomerPhone $phone): LegalRepresentative
    {
        $this->phone = $phone;

        return $this;
    }

    public function setAddress(CustomerAddress $address): LegalRepresentative
    {
        $this->address = $address;

        return $this;
    }

    public function setBirthDate(string $birthDate): LegalRepresentative
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function setMotherName(string $motherName): LegalRepresentative
    {
        $this->motherName = $motherName;

        return $this;
    }

    public function setEmail(string $email): LegalRepresentative
    {
        $this->email = $email;

        return $this;
    }

    public function setPepLevel(string $pepLevel): LegalRepresentative
    {
        $this->pepLevel = $pepLevel;

        return $this;
    }

    public function setDeclaredIncome(string $declaredIncome): LegalRepresentative
    {
        $this->declaredIncome = $declaredIncome;

        return $this;
    }

    public function setSelfieToken(string $selfieToken): LegalRepresentative
    {
        $this->selfieToken = $selfieToken;

        return $this;
    }

    public function setIdCardFrontToken(string $idCardFrontToken): LegalRepresentative
    {
        $this->idCardFrontToken = $idCardFrontToken;

        return $this;
    }

    public function setIdCardBackToken(string $idCardBackToken): LegalRepresentative
    {
        $this->idCardBackToken = $idCardBackToken;

        return $this;
    }

    public function setOccupation(string $occupation): LegalRepresentative
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getDocumentNumber(): array
    {
        return [
            'value' => $this->documentNumber,
            'type' => 'CPF', //Accepts only cpf
        ];
    }

    public function getRegisterName(): string
    {
        return $this->registerName;
    }

    public function getSocialName(): string
    {
        return $this->socialName;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getMotherName(): string
    {
        return $this->motherName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPepLevel(): string
    {
        return $this->pepLevel;
    }

    public function getDeclaredIncome(): string
    {
        return $this->declaredIncome;
    }

    public function getSelfieToken(): string
    {
        return $this->selfieToken;
    }

    public function getIdCardFrontToken(): string
    {
        return $this->idCardFrontToken;
    }

    public function getIdCardBackToken(): string
    {
        return $this->idCardBackToken;
    }

    public function getOccupation(): string
    {
        return $this->occupation;
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone->toArray(),
            'address' => $this->address->toArray(),
            'socialName' => $this->socialName,
            'registerName' => $this->registerName,
            'birthDate' => $this->birthDate,
            'motherName' => $this->motherName,
            'email' => $this->email,
            'declaredIncome' => $this->declaredIncome,
            'documentNumber' => $this->documentNumber,
            'document' => $this->getDocumentNumber(),
            'occupation' => $this->occupation,
            'pep' => [
                'level' => $this->pepLevel,
            ],
            'documentation' => [
                'selfie' => $this->selfieToken,
                'idCardFront' => $this->idCardFrontToken,
                'idCardBack' => $this->idCardBackToken,
            ],
        ];
    }
}
