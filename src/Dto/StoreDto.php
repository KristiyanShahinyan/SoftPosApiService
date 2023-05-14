<?php

namespace App\Dto;

/**
 * Class StoreDto
 * @package App\Dto
 */
class StoreDto
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $cardAcceptorIdentificationCode;

    /**
     * @var string|null
     */
    private $cardAcceptorIdentificationCode3ds;

    /**
     * @var string|null
     */
    private $cardAcceptorName;

    /**
     * @var string|null
     */
    private $cardAcceptorCity;

    /**
     * @var string|null
     */
    private $cardAcceptorCountry;

    /**
     * @var bool|null
     */
    private $isActive;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var int|null
     */
    private $merchant;

    private ?MerchantCategoryCodeDto $merchantCategoryCode = null;

    protected TimezoneDto $timezone;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getCardAcceptorIdentificationCode(): ?string
    {
        return $this->cardAcceptorIdentificationCode;
    }

    /**
     * @param string|null $cardAcceptorIdentificationCode
     */
    public function setCardAcceptorIdentificationCode(?string $cardAcceptorIdentificationCode): void
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
     * @return string|null
     */
    public function getCardAcceptorName(): ?string
    {
        return $this->cardAcceptorName;
    }

    /**
     * @param string|null $cardAcceptorName
     */
    public function setCardAcceptorName(?string $cardAcceptorName): void
    {
        $this->cardAcceptorName = $cardAcceptorName;
    }

    /**
     * @return string|null
     */
    public function getCardAcceptorCity(): ?string
    {
        return $this->cardAcceptorCity;
    }

    /**
     * @param string|null $cardAcceptorCity
     */
    public function setCardAcceptorCity(?string $cardAcceptorCity): void
    {
        $this->cardAcceptorCity = $cardAcceptorCity;
    }

    /**
     * @return string|null
     */
    public function getCardAcceptorCountry(): ?string
    {
        return $this->cardAcceptorCountry;
    }

    /**
     * @param string|null $cardAcceptorCountry
     */
    public function setCardAcceptorCountry(?string $cardAcceptorCountry): void
    {
        $this->cardAcceptorCountry = $cardAcceptorCountry;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return int|null
     */
    public function getMerchant(): ?int
    {
        return $this->merchant;
    }

    /**
     * @param int|null $merchant
     */
    public function setMerchant(?int $merchant): void
    {
        $this->merchant = $merchant;
    }

    public function getMerchantCategoryCode(): ?MerchantCategoryCodeDto
    {
        return $this->merchantCategoryCode;
    }

    public function setMerchantCategoryCode(MerchantCategoryCodeDto $mcc): void
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
}
