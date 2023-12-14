<?php

namespace WeDevBr\Bankly\Inputs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Business Customer class
 *
 * PHP version 8.0|8.1
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <marco.santos@wedev.softwarem>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class BusinessCustomer implements Arrayable
{
    protected string $businessName;

    protected string $tradingName;

    protected string $businessEmail;

    protected string $businessType;

    protected string $businessSize;

    protected string $declaredAnnualBilling;

    protected CustomerAddress $businessAddress;

    protected LegalRepresentative $legalRepresentative;

    public function setBusinessName(string $businessName): BusinessCustomer
    {
        $this->businessName = $businessName;

        return $this;
    }

    public function setTradingName(string $tradingName): BusinessCustomer
    {
        $this->tradingName = $tradingName;

        return $this;
    }

    public function setBusinessEmail(string $businessEmail): BusinessCustomer
    {
        $this->businessEmail = $businessEmail;

        return $this;
    }

    public function setBusinessType(string $businessType): BusinessCustomer
    {
        $this->businessType = $businessType;

        return $this;
    }

    public function setBusinessSize(string $businessSize): BusinessCustomer
    {
        $this->businessSize = $businessSize;

        return $this;
    }

    public function setDeclaredAnnualBilling(string $declaredAnnualBilling): BusinessCustomer
    {
        $this->declaredAnnualBilling = $declaredAnnualBilling;

        return $this;
    }

    public function setBusinessAddress(CustomerAddress $businessAddress): BusinessCustomer
    {
        $this->businessAddress = $businessAddress;

        return $this;
    }

    public function setLegalRepresentative(LegalRepresentative $legalRepresentative): BusinessCustomer
    {
        $this->legalRepresentative = $legalRepresentative;

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

    public function toArray(): array
    {
        return [
            'businessAddress' => $this->businessAddress->toArray(),
            'legalRepresentative' => $this->legalRepresentative->toArray(),
            'tradingName' => $this->tradingName,
            'businessName' => $this->businessName,
            'businessEmail' => $this->businessEmail,
            'declaredAnnualBilling' => $this->declaredAnnualBilling,
            'businessSize' => $this->businessSize,
            'businessType' => $this->businessType,
        ];
    }
}
