<?php

namespace WeDevBr\Bankly\Inputs;

/**
 * Business LegalRepresentative class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <marco.santos@wedev.softwarem>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class LegalRepresentative
{
    /** @var string */
    public $documentNumber;

    /** @var string */
    public $registerName;

    /** @var string */
    public $socialName;

    /** @var LegalRepresentativePhone */
    public $phone;

    /** @var LegalRepresentativeAddress */
    public $address;

    /** @var string */
    public $birthDate;

    /** @var string */
    public $motherName;

    /** @var string */
    public $email;

    /** @var string */
    public $pepLevel;

    /** @var string */
    protected $selfieToken;

    /** @var string */
    protected $idCardFrontToken;

    /** @var string */
    protected $idCardBackToken;

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
    public function setSelfieToken(string $selfieToken): LegalRepresentative
    {
        $this->selfieToken = $selfieToken;
        return $this;
    }

    /**
     * @param string $declaredIncome
     * @return LegalRepresentative
     */
    public function setIdCardFrontToken(string $idCardFrontToken): LegalRepresentative
    {
        $this->idCardFrontToken = $idCardFrontToken;
        return $this;
    }

    /**
     * @param string $declaredIncome
     * @return LegalRepresentative
     */
    public function setIdCardBackToken(string $idCardBackToken): LegalRepresentative
    {
        $this->idCardBackToken = $idCardBackToken;
        return $this;
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
     * @return array
     */
    public function atoArray(): array
    {
        return [
            'phone' => $this->phone->toArray(),
            'address' => $this->address->toArray(),
            'socialName' => $this->socialName,
            'registerName' => $this->registerName,
            'birthDate' => $this->birthDate,
            'motherName' => $this->motherName,
            'email' => $this->email,
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
