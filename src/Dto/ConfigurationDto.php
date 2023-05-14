<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ConfigurationDto
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     */
    protected $acquiringInstitutionIdentificationCode;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=8)
     */
    protected $posTerminalIdCode;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     */
    protected $cardAcceptorIdentificationCode;


    /**
     * @var string
     * @Assert\Length(min=4)
     */
    protected $cardAcceptorIdentificationCode3ds;


    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=25)
     */
    protected $cardAcceptorName;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(max=13)
     */
    protected $cardAcceptorCity;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=2)
     */
    protected $cardAcceptorCountry;

    /**
     * @var string
     * @Assert\Length(min=4, max=4)
     */
    protected $merchantCategoryCode;

    protected TimezoneDto $timezone;

    /**
     * @var string|null
     */
    protected $systemTraceAuditNumber;

    protected CurrencyDto $currency;

    /** @var int|null */
    protected $terminalScaType;

    /** @var string|null */
    protected $affiliate = null;

    /** @var string|null */
    protected $merchantToken = null;

    /** @var string|null */
    protected $terminalToken = null;

    /** @var string|null */
    protected $storeToken = null;

    protected ?array $acquirerSpecificData = null;

    /** @var string|null */
    protected $instance = null;

    /**
     * @return string
     */
    public function getAcquiringInstitutionIdentificationCode(): string
    {
        return $this->acquiringInstitutionIdentificationCode;
    }

    /**
     * @param string $acquiringInstitutionIdentificationCode
     */
    public function setAcquiringInstitutionIdentificationCode(string $acquiringInstitutionIdentificationCode): void
    {
        $this->acquiringInstitutionIdentificationCode = $acquiringInstitutionIdentificationCode;
    }

    /**
     * @return string
     */
    public function getPosTerminalIdCode(): string
    {
        return $this->posTerminalIdCode;
    }

    /**
     * @param string $posTerminalIdCode
     */
    public function setPosTerminalIdCode(string $posTerminalIdCode): void
    {
        $this->posTerminalIdCode = $posTerminalIdCode;
    }

    /**
     * @return string
     */
    public function getCardAcceptorIdentificationCode(): string
    {
        return $this->cardAcceptorIdentificationCode;
    }

    /**
     * @param string $cardAcceptorIdentificationCode
     */
    public function setCardAcceptorIdentificationCode(string $cardAcceptorIdentificationCode): void
    {
        $this->cardAcceptorIdentificationCode = $cardAcceptorIdentificationCode;
    }

     /**
     * @return string|null
     */
    public function getCardAcceptorIdentificationCode3ds(): ?string
    {
        return $this->cardAcceptorIdentificationCode3ds;
    }

    /**
     * @param string|null $cardAcceptorIdentificationCode3ds
     */
    public function setCardAcceptorIdentificationCode3ds(?string $cardAcceptorIdentificationCode3ds): void
    {
        $this->cardAcceptorIdentificationCode3ds = $cardAcceptorIdentificationCode3ds;
    }

    /**
     * @return string
     */
    public function getCardAcceptorName(): string
    {
        return $this->cardAcceptorName;
    }

    /**
     * @param string $cardAcceptorName
     */
    public function setCardAcceptorName(string $cardAcceptorName): void
    {
        $this->cardAcceptorName = $cardAcceptorName;
    }

    /**
     * @return string
     */
    public function getCardAcceptorCity(): string
    {
        return $this->cardAcceptorCity;
    }

    /**
     * @param string $cardAcceptorCity
     */
    public function setCardAcceptorCity(string $cardAcceptorCity): void
    {
        $this->cardAcceptorCity = $cardAcceptorCity;
    }

    /**
     * @return string
     */
    public function getCardAcceptorCountry(): string
    {
        return $this->cardAcceptorCountry;
    }

    /**
     * @param string $cardAcceptorCountry
     */
    public function setCardAcceptorCountry(string $cardAcceptorCountry): void
    {
        $this->cardAcceptorCountry = $cardAcceptorCountry;
    }

    public function getMerchantCategoryCode()
    {
        return $this->merchantCategoryCode;
    }

    public function setMerchantCategoryCode(string $mcc)
    {
        $this->merchantCategoryCode = $mcc;
    }

    public function getTimezone(): TimezoneDto
    {
        return $this->timezone;
    }

    public function setTimezone(TimezoneDto $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * @return string|null
     */
    public function getSystemTraceAuditNumber(): ?string
    {
        return $this->systemTraceAuditNumber;
    }

    /**
     * @param string|null $systemTraceAuditNumber
     */
    public function setSystemTraceAuditNumber(?string $systemTraceAuditNumber): void
    {
        $this->systemTraceAuditNumber = $systemTraceAuditNumber;
    }

    /**
     * @return CurrencyDto
     */
    public function getCurrency(): CurrencyDto
    {
        return $this->currency;
    }

    /**
     * @param CurrencyDto $currency
     */
    public function setCurrency(CurrencyDto $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return int|null
     */
    public function getTerminalScaType(): ?int
    {
        return $this->terminalScaType;
    }

    /**
     * @param int|null $terminalScaType
     */
    public function setTerminalScaType(?int $terminalScaType): void
    {
        $this->terminalScaType = $terminalScaType;
    }

    /**
     * @return string|null
     */
    public function getAffiliate(): ?string
    {
        return $this->affiliate;
    }

    /**
     * @param string|null $affiliate
     */
    public function setAffiliate(?string $affiliate): void
    {
        $this->affiliate = $affiliate;
    }

    /**
     * @return string|null
     */
    public function getMerchantToken(): ?string
    {
        return $this->merchantToken;
    }

    /**
     * @param string|null $merchantToken
     */
    public function setMerchantToken(?string $merchantToken): void
    {
        $this->merchantToken = $merchantToken;
    }

    /**
     * @return string|null
     */
    public function getTerminalToken(): ?string
    {
        return $this->terminalToken;
    }

    /**
     * @param string|null $terminalToken
     */
    public function setTerminalToken(?string $terminalToken): void
    {
        $this->terminalToken = $terminalToken;
    }

    /**
     * @return string|null
     */
    public function getStoreToken(): ?string
    {
        return $this->storeToken;
    }

    /**
     * @param string|null $storeToken
     */
    public function setStoreToken(?string $storeToken): void
    {
        $this->storeToken = $storeToken;
    }

    /**
     * @return array|null
     */
    public function getAcquirerSpecificData(): ?array
    {
        return $this->acquirerSpecificData;
    }

    /**
     * @param array|null $acquirerSpecificData
     */
    public function setAcquirerSpecificData(?array $acquirerSpecificData): void
    {
        $this->acquirerSpecificData = $acquirerSpecificData;
    }

    /**
     * @return string|null
     */
    public function getInstance(): ?string
    {
        return $this->instance;
    }

    /**
     * @param string|null $instance
     */
    public function setInstance(?string $instance): void
    {
        $this->instance = $instance;
    }
}
