<?php

namespace App\Dto\Request;

use Phos\Dto\Request\AttributesDto;
use Symfony\Component\Validator\Constraints as Assert;

class StoreFilterDto extends AttributesDto
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var int|null
     * @Assert\NotNull()
     */
    private $merchant;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @var array|null
     */
    private $range;

    /**
     * @var int|null
     */
    private $user;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
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
     * @return array|null
     */
    public function getRange(): ?array
    {
        return $this->range;
    }

    /**
     * @param array|null $range
     */
    public function setRange(?array $range): void
    {
        $this->range = $range;
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
