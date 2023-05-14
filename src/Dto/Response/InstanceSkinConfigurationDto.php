<?php

namespace App\Dto\Response;

/**
 * Class SkinModel
 * @package App\Dto\Response
 */
class InstanceSkinConfigurationDto
{
    private ?InstanceColorsDto $colors;

    private ?InstanceResourcesDto $resources;

    public function __construct()
    {
        $this->colors = new InstanceColorsDto();
    }

    /**
     * @return InstanceColorsDto|null
     */
    public function getColors(): ?InstanceColorsDto
    {
        return $this->colors;
    }

    /**
     * @param InstanceColorsDto|null $colors
     */
    public function setColors(?InstanceColorsDto $colors): void
    {
        $this->colors = $colors;
    }

    /**
     * @return InstanceResourcesDto|null
     */
    public function getResources(): ?InstanceResourcesDto
    {
        return $this->resources;
    }

    /**
     * @param InstanceResourcesDto|null $resources
     */
    public function setResources(?InstanceResourcesDto $resources): void
    {
        $this->resources = $resources;
    }

}
