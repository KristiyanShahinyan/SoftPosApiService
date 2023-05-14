<?php

namespace App\Security;

class Merchants
{
    /**
     * @var Merchant|null
     */
    private $merchant;

    /**
     * @var array|null
     */
    private $roles;

    /**
     * @return Merchant|null
     */
    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    /**
     * @param Merchant|null $merchant
     */
    public function setMerchant(?Merchant $merchant): void
    {
        $this->merchant = $merchant;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param array|null $roles
     */
    public function setRoles(?array $roles): void
    {
        $this->roles = $roles;
    }

}
