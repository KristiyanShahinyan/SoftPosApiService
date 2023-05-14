<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AccessRequestDto
 * @package App\Dto\Request
 */
class AccessRequestDto
{
    /**
     * @var int|null
     */
    private $user;

    /**
     * @var int|null
     * @Assert\NotNull()
     */
    private $merchant;

    /**
     * @var int|null
     */
    private $store;

    /**
     * @var int|null
     */
    private $terminal;

    /**
     * @return int|null
     */
    public function getUser(): ?int
    {
        return $this->user;
    }

    /**
     * @param int|null $user
     */
    public function setUser(?int $user): void
    {
        $this->user = $user;
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

    /**
     * @return int|null
     */
    public function getStore(): ?int
    {
        return $this->store;
    }

    /**
     * @param int|null $store
     */
    public function setStore(?int $store): void
    {
        $this->store = $store;
    }

    /**
     * @return int|null
     */
    public function getTerminal(): ?int
    {
        return $this->terminal;
    }

    /**
     * @param int|null $terminal
     */
    public function setTerminal(?int $terminal): void
    {
        $this->terminal = $terminal;
    }

}
