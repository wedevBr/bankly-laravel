<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Corporation Corporation Business Customer class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Judson Bandeira <judson@iholdbank.digital>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class CorporationBusinessCustomer implements Arrayable
{
    protected string $businessName;

    protected ?string $tradingName;

    protected string $businessEmail;

    protected string $businessType;

    protected string $businessSize;

    protected string $cnaeCode;

    protected string $declaredAnnualBilling;

    protected string $legalNature;

    protected string $openingDate;

    protected CustomerAddress $businessAddress;

    protected CustomerPhone $phone;
    
    protected array $legalRepresentatives;

    protected ?array $owners;

    protected Documentation $documentation;

    public function setBusinessName(string $businessName): CorporationBusinessCustomer
    {
        $this->businessName = $businessName;

        return $this;
    }

    public function setTradingName(string $tradingName): CorporationBusinessCustomer
    {
        $this->tradingName = $tradingName;

        return $this;
    }

    public function setBusinessEmail(string $businessEmail): CorporationBusinessCustomer
    {
        $this->businessEmail = $businessEmail;

        return $this;
    }

    public function setBusinessType(string $businessType): CorporationBusinessCustomer
    {
        $this->businessType = $businessType;

        return $this;
    }

    public function setBusinessSize(string $businessSize): CorporationBusinessCustomer
    {
        $this->businessSize = $businessSize;

        return $this;
    }

    public function setCnaeCode(string $cnaeCode): CorporationBusinessCustomer
    {
        $this->cnaeCode = $cnaeCode;

        return $this;
    }

    public function setDeclaredAnnualBilling(string $declaredAnnualBilling): CorporationBusinessCustomer
    {
        $this->declaredAnnualBilling = $declaredAnnualBilling;

        return $this;
    }

    public function setBusinessAddress(CustomerAddress $businessAddress): CorporationBusinessCustomer
    {
        $this->businessAddress = $businessAddress;

        return $this;
    }

    public function setDocumentation(Documentation $documentation): CorporationBusinessCustomer
    {
        $this->documentation = $documentation;

        return $this;
    }

    public function setLegalNature(string $legalNature): CorporationBusinessCustomer
    {
        $this->legalNature = $legalNature;

        return $this;
    }

    public function setOpeningDate(string $openingDate): CorporationBusinessCustomer
    {
        $this->openingDate = $openingDate;

        return $this;
    }


    public function addLegalRepresentative(LegalRepresentative $legalRepresentative): CorporationBusinessCustomer
    {
        $this->legalRepresentatives[] = $legalRepresentative;

        return $this;
    }

    public function addOwner(LegalRepresentative $legalRepresentative): CorporationBusinessCustomer
    {
        $this->owners[] = $legalRepresentative;

        return $this;
    }

    public function getBusinessName(): string
    {
        return $this->businessName;
    }

    public function getTradingName(): string
    {
        return $this->tradingName;
    }

    public function getBusinessEmail(): string
    {
        return $this->businessEmail;
    }

    public function getBusinessType(): string
    {
        return $this->businessType;
    }

    public function getBusinessSize(): string
    {
        return $this->businessSize;
    }

    public function getDeclaredAnnualBilling(): string
    {
        return $this->declaredAnnualBilling;
    }

    public function getLegalRepresentatives(): array
    {
        return $this->legalRepresentatives;
    }

    public function toArray(): array
    {
        return [
            'businessName' => $this->businessName,
            'tradingName' => $this->tradingName,
            'businessEmail' => $this->businessEmail,
            'businessType' => $this->businessType,
            'businessSize' => $this->businessSize,
            'cnaeCode' => $this->cnaeCode,
            'declaredAnnualBilling' => $this->declaredAnnualBilling,
            'legalNature' => $this->legalNature,
            'openingDate' => $this->openingDate,
            'businessAddress' => $this->businessAddress->toArray(),
            'phone' => $this->phone->toArray(),
            'legalRepresentatives' => $this->legalRepresentative->toArray(),
            'owners' => $this->owners->toArray(),
            'documentation' => $this->documentation->toArray(),
        ];
    }
}
