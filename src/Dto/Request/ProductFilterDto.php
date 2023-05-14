<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;

class ProductFilterDto implements DtoInterface
{
    /**
     */
    private $merchant;

    /**
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @param mixed $merchant
     */
    public function setMerchant($merchant): void
    {
        $this->merchant = $merchant;
    }
}
