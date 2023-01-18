<?php

namespace WeDevBr\Bankly\Inputs;

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
class LegalRepresentative
{
    /** @var string */
    protected $documentNumber;

    /** @var string */
    protected $registerName;

    /** @var string */
    protected $socialName;

    /** @var CustomerPhone */
    protected $phone;

    /** @var CustomerAddress */
    protected $address;

    /** @var string */
    protected $birthDate;

    /** @var string */
    protected $motherName;

    /** @var string */
    protected $email;

    /** @var string */
    protected $pepLevel;

    /** @var string */
    protected $declaredIncome;

    /** @var string */
    protected $selfieToken;

    /** @var string */
    protected $idCardFrontToken;

    /** @var string */
    protected $idCardBackToken;

    /** @var string */
    protected $ocupation;

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
     * @param string $ocupation
     * @return LegalRepresentative
     */
    public function setOcupation(string $ocupation): LegalRepresentative
    {
        $this->ocupation = $ocupation;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
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
    public function getOcupation(): string
    {
        return $this->ocupation;
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
            'pep' => [
                'level' => $this->pepLevel
            ],
            'documentation' => [
                'selfie' => $this->selfieToken,
                'idCardFront' => $this->idCardFrontToken,
                'idCardBack' => $this->idCardBackToken,
            ]
        ];
    }
}
