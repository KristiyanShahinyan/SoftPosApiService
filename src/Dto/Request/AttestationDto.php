<?php

namespace App\Dto\Request;

/**
 * Class AttestationDto
 * @package App\Dto\Request
 */
class AttestationDto
{
    /**
     * @var string
     */
    protected $appType;

    /**
     * @var string
     */
    protected $deviceId;

    /**
     * @return string
     */
    public function getAppType(): string
    {
        return $this->appType;
    }

    /**
     * @param string $appType
     */
    public function setAppType(string $appType): void
    {
        $this->appType = $appType;
    }

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     */
    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

}
