<?php

namespace App\Dto\Response;

/**
 * Class ResourcesModel
 * @package App\Dto\Response
 */
class InstanceResourcesDto
{
    /**
     * @var string|null
     */
    private $appLoadingUrl;

    /**
     * @var string|null
     */
    private $appIconUrl;

    /**
     * @var string|null
     */
    private $appLogoUrl;

    /**
     * @return string|null
     */
    public function getAppLoadingUrl(): ?string
    {
        return $this->appLoadingUrl;
    }

    /**
     * @param string|null $appLoadingUrl
     */
    public function setAppLoadingUrl(?string $appLoadingUrl): void
    {
        $this->appLoadingUrl = $appLoadingUrl;
    }

    /**
     * @return string|null
     */
    public function getAppIconUrl(): ?string
    {
        return $this->appIconUrl;
    }

    /**
     * @param string|null $appIconUrl
     */
    public function setAppIconUrl(?string $appIconUrl): void
    {
        $this->appIconUrl = $appIconUrl;
    }

    /**
     * @return string|null
     */
    public function getAppLogoUrl(): ?string
    {
        return $this->appLogoUrl;
    }

    /**
     * @param string|null $appLogoUrl
     */
    public function setAppLogoUrl(?string $appLogoUrl): void
    {
        $this->appLogoUrl = $appLogoUrl;
    }

}
