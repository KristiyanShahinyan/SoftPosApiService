<?php

namespace App\Security;

use App\Dto\AffiliateDto;
use App\Dto\InstanceDto;

class Merchant
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var bool|null
     */
    private $isEnabled;

    /**
     * @var bool|null
     */
    private $isApproved;

    /**
     * @var array|null
     */
    private $stores;

    /**
     * @var Company|null
     */
    private $company;

    private ?InstanceDto $instance = null;

    /**
     * @var string|null
     */
    private $posTerminalIdCode3ds;

    /** @var int|null */
    private $scaType;

    private ?AffiliateDto $affiliate = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
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
     * @return string|null ?string
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
     * @return bool
     */
    public function isEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled(?bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return bool
     */
    public function isApproved(): ?bool
    {
        return $this->isApproved;
    }

    /**
     * @param bool $isApproved
     */
    public function setIsApproved(?bool $isApproved): void
    {
        $this->isApproved = $isApproved;
    }

    /**
     * @return array|null
     */
    public function getStores(): ?array
    {
        return $this->stores;
    }

    /**
     * @param array|null $stores
     */
    public function setStores(?array $stores): void
    {
        $this->stores = $stores;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     */
    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    public function getInstance(): ?InstanceDto
    {
        return $this->instance;
    }

    public function setInstance(?InstanceDto $instance): void
    {
        $this->instance = $instance;
    }

    /**
     * @return string|null
     */
    public function getPosTerminalIdCode3ds(): ?string
    {
        return $this->posTerminalIdCode3ds;
    }

    /**
     * @param string|null $posTerminalIdCode3ds
     */
    public function setPosTerminalIdCode3ds(?string $posTerminalIdCode3ds): void
    {
        $this->posTerminalIdCode3ds = $posTerminalIdCode3ds;
    }

    /**
     * @return int|null
     */
    public function getScaType(): ?int
    {
        return $this->scaType;
    }

    /**
     * @param int|null $scaType
     */
    public function setScaType(?int $scaType): void
    {
        $this->scaType = $scaType;
    }

    /**
     * @return AffiliateDto|null
     */
    public function getAffiliate(): ?AffiliateDto
    {
        return $this->affiliate;
    }

    /**
     * @param AffiliateDto|null $affiliate
     */
    public function setAffiliate(?AffiliateDto $affiliate): void
    {
        $this->affiliate = $affiliate;
    }

}
