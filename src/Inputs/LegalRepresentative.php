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
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class LegalRepresentative implements Arrayable
{
    /** @var string */
    protected string $documentNumber;

    /** @var string */
    protected string $registerName;

    /** @var ?string */
    protected ?string $socialName = null;

    /** @var CustomerPhone */
    protected CustomerPhone $phone;

    /** @var CustomerAddress */
    protected CustomerAddress $address;

    /** @var string */
    protected string $birthDate;

    /** @var string */
    protected string $motherName;

    /** @var string */
    protected string $email;

    /** @var string */
    protected string $pepLevel;

    /** @var string */
    protected string $declaredIncome;

    /** @var string */
    protected string $selfieToken;

    /** @var string */
    protected string $idCardFrontToken;

    /** @var string */
    protected string $idCardBackToken;

    /** @var string */
    protected string $occupation;

    /**
     * @param string $documentNumber
     * @return LegalRepresentative
     */
    public function setDocumentNumber(string $documentNumber): LegalRepresentative
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * @param string $name
     * @return LegalRepresentative
     */
    public function setRegisterName(string $name): LegalRepresentative
    {
        $this->registerName = $name;
        return $this;
    }

    /**
     * @param string $name
     * @return LegalRepresentative
     */
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

    /**
     * @param string $birthDate
     * @return LegalRepresentative
     */
    public function setBirthDate(string $birthDate): LegalRepresentative
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @param string $motherName
     * @return LegalRepresentative
     */
    public function setMotherName(string $motherName): LegalRepresentative
    {
        $this->motherName = $motherName;
        return $this;
    }

    /**
     * @param string $email
     * @return LegalRepresentative
     */
    public function setEmail(string $email): LegalRepresentative
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $pepLevel
     * @return LegalRepresentative
     */
    public function setPepLevel(string $pepLevel): LegalRepresentative
    {
        $this->pepLevel = $pepLevel;
        return $this;
    }

    /**
     * @param string $declaredIncome
     * @return LegalRepresentative
     */
    public function setDeclaredIncome(string $declaredIncome): LegalRepresentative
    {
        $this->declaredIncome = $declaredIncome;
        return $this;
    }

    /**
     * @param string $selfieToken
     * @return LegalRepresentative
     */
    public function setSelfieToken(string $selfieToken): LegalRepresentative
    {
        $this->selfieToken = $selfieToken;
        return $this;
    }

    /**
     * @param string $idCardFrontToken
     * @return LegalRepresentative
     */
    public function setIdCardFrontToken(string $idCardFrontToken): LegalRepresentative
    {
        $this->idCardFrontToken = $idCardFrontToken;
        return $this;
    }

    /**
     * @param string $idCardBackToken
     * @return LegalRepresentative
     */
    public function setIdCardBackToken(string $idCardBackToken): LegalRepresentative
    {
        $this->idCardBackToken = $idCardBackToken;
        return $this;
    }

    /**
     * @param string $occupation
     * @return LegalRepresentative
     */
    public function setOccupation(string $occupation): LegalRepresentative
    {
        $this->occupation = $occupation;
        return $this;
    }

    /**
     * @return array
     */
    public function getDocumentNumber(): array
    {
        return [
            'value' => $this->documentNumber,
            'type' => 'CPF', //Accepts only cpf
        ];
    }

    /**
     * @return string
     */
    public function getRegisterName(): string
    {
        return $this->registerName;
    }

    /**
     * @return string
     */
    public function getSocialName(): string
    {
        return $this->socialName;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getMotherName(): string
    {
        return $this->motherName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPepLevel(): string
    {
        return $this->pepLevel;
    }

    /**
     * @return string
     */
    public function getDeclaredIncome(): string
    {
        return $this->declaredIncome;
    }

    /**
     * @return string
     */
    public function getSelfieToken(): string
    {
        return $this->selfieToken;
    }

    /**
     * @return string
     */
    public function getIdCardFrontToken(): string
    {
        return $this->idCardFrontToken;
    }

    /**
     * @return string
     */
    public function getIdCardBackToken(): string
    {
        return $this->idCardBackToken;
    }

    /**
     * @return string
     */
    public function getOccupation(): string
    {
        return $this->occupation;
    }

    /**
     * @return array
     */
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
                'level' => $this->pepLevel
            ],
            'documentation' => [
                'selfie' => $this->selfieToken,
                'idCardFront' => $this->idCardFrontToken,
                'idCardBack' => $this->idCardBackToken,
            ],
        ];
    }
}
