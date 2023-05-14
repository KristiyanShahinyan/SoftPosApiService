<?php

namespace App\Dto\Request;

use Phos\Dto\Request\AttributesDto;

/**
 * Class TerminalFilterDto
 * @package App\Dto\Request
 */
class TerminalFilterDto extends AttributesDto
{

    /**
     * @var bool|null
     */
    private $isActive;

    /**
     * @var string|null
     */
    private $posTerminalIdCode;

    /**
     * @var int|null
     */
    private $store;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var int|null
     */
    private $user;

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

}
