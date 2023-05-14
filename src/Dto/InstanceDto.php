<?php

namespace App\Dto;

class InstanceDto implements DtoInterface
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var bool
     */
    private bool $is_enabled;

    /**
     * @var bool
     */
    private bool $allow_api_access;

    /**
     * @var string
     */
    private string $acquiring_institution_identification_code;

    /** @var int|null */
    private $scaType;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isIsEnabled(): bool
    {
        return $this->is_enabled;
    }

    /**
     * @param bool $is_enabled
     */
    public function setIsEnabled(bool $is_enabled): void
    {
        $this->is_enabled = $is_enabled;
    }

    /**
     * @return bool
     */
    public function isAllowApiAccess(): bool
    {
        return $this->allow_api_access;
    }

    /**
     * @param bool $allow_api_access
     */
    public function setAllowApiAccess(bool $allow_api_access): void
    {
        $this->allow_api_access = $allow_api_access;
    }

    /**
     * @return string
     */
    public function getAcquiringInstitutionIdentificationCode(): string
    {
        return $this->acquiring_institution_identification_code;
    }

    /**
     * @param string $acquiring_institution_identification_code
     */
    public function setAcquiringInstitutionIdentificationCode(string $acquiring_institution_identification_code): void
    {
        $this->acquiring_institution_identification_code = $acquiring_institution_identification_code;
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
}
