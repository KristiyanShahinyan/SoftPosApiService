<?php

namespace App\Dto;

/**
 * Class TerminalDto
 * @package App\Dto
 */
class TerminalDto
{
    /**
     * @var string|null
     */
    private $alias;

    /**
     * @var string|null
     */
    private $posTerminalIdCode;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var bool|null
     */
    private $isActive;

    /**
     * @var string
     */
    private $systemTraceAuditNumber;

    /**
     * @var string|null
     */
    private $posTerminalIdCode3ds;

    /** @var int|null */
    private $scaType;

    private ?array $acquirerSpecificData = null;

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string|null $alias
     */
    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string|null
     */
    public function getPosTerminalIdCode(): ?string
    {
        return $this->posTerminalIdCode;
    }

    /**
     * @param string|null $posTerminalIdCode
     */
    public function setPosTerminalIdCode(?string $posTerminalIdCode): void
    {
        $this->posTerminalIdCode = $posTerminalIdCode;
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
}
