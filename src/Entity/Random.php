<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Phos\Entity\BaseEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RandomRepository")
 */
class Random extends BaseEntity
{
    /**
     * @var string
     * @ORM\Column(name="base_64_encoded_random", length=64)
     */
    protected string $base64EncodedRandom;

    /**
     * @var DateTime
     * @ORM\Column(name="expires_on", type="datetime")
     */
    protected DateTime $expiresOn;

    public function getBase64EncodedRandom(): string
    {
        return $this->base64EncodedRandom;
    }

    public function setBase64EncodedRandom(string $base64EncodedRandom): void
    {
        $this->base64EncodedRandom = $base64EncodedRandom;
    }

    public function getExpiresOn(): DateTime
    {
        return $this->expiresOn;
    }

    public function setExpiresOn(DateTime $expiresOn): void
    {
        $this->expiresOn = $expiresOn;
    }
}