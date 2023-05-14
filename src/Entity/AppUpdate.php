<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Phos\Annotation\Cache\Cache;
use Phos\Annotation\Cache\Settings;
use Phos\Cache\CacheableInterface;
use Phos\Entity\BaseEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppUpdateRepository")
 *
 * @Settings(identity="version")
 */
class AppUpdate extends BaseEntity implements CacheableInterface
{
    /**
     * @ORM\Column(type="integer")
     * @Groups({"index","show","create","update"})
     * @Cache()
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"index","show","create","update"})
     * @Cache()
     */
    private $hash;

    /**
     * @var string|null
     * @Groups({"index","show","create","update"})
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"index","show","create","update"})
     * @Cache()
     */
    private $force;

    /**
     * @var DateTimeInterface|null
     * @Groups({"index","show","create","update"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastDateBeforeForce;

    /**
     * @var int|null
     * @Groups({"index","show","create","update"})
     * @ORM\Column(type="integer")
     */
    private $instance;

    /**
     * @Groups({"index","show","update"})
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn;
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn(DateTime $updatedOn): void
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return bool|null
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool|null $isDeleted
     */
    public function setIsDeleted(?bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * @param mixed $force
     */
    public function setForce($force): void
    {
        $this->force = $force;
    }

    /**
     * @return DateTimeInterface
     */
    public function getLastDateBeforeForce(): ?DateTimeInterface
    {
        return $this->lastDateBeforeForce;
    }

    /**
     * @param DateTimeInterface $lastDateBeforeForce
     */
    public function setLastDateBeforeForce(DateTimeInterface $lastDateBeforeForce): void
    {
        $this->lastDateBeforeForce = $lastDateBeforeForce;
    }

    /**
     * @return int|null
     */
    public function getInstance(): ?int
    {
        return $this->instance;
    }

    /**
     * @param int|null $instance
     */
    public function setInstance(?int $instance): void
    {
        $this->instance = $instance;
    }

}
