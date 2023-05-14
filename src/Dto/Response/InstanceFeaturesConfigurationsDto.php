<?php

namespace App\Dto\Response;

/**
 * Class InstanceFeaturesConfigurationsModel
 * @package App\Dto\Response
 */
class InstanceFeaturesConfigurationsDto
{
    /**
     * @var bool|null
     */
    private $alipay = false;

    /**
     * @var bool|null
     */
    private $charity = false;

    /**
     * @return bool|null
     */
    public function getAlipay(): ?bool
    {
        return $this->alipay;
    }

    /**
     * @param bool|null $alipay
     */
    public function setAlipay(?bool $alipay): void
    {
        $this->alipay = $alipay;
    }

    /**
     * @return bool|null
     */
    public function getCharity(): ?bool
    {
        return $this->charity;
    }

    /**
     * @param bool|null $charity
     */
    public function setCharity(?bool $charity): void
    {
        $this->charity = $charity;
    }

}
