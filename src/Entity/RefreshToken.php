<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Refresh Token.
 *
 * @ORM\Entity(repositoryClass="Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RefreshToken implements RefreshTokenInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $deviceId;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank()
     */
    private $refreshToken;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $valid;

    /**
     * @return string
     */
    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     */
    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * Get id.
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get valid.
     *
     * @return DateTime
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set valid.
     *
     * @param DateTime $valid
     *
     * @return self
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Check if is a valid refresh token.
     *
     * @return bool
     * @throws Exception
     */
    public function isValid()
    {
        return $this->valid >= new DateTime();
    }

    /**
     * @return string Refresh Token
     */
    public function __toString()
    {
        return $this->getRefreshToken();
    }

    /**
     * Get refreshToken.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set refreshToken.
     *
     * @param string $refreshToken
     *
     * @return self
     */
    public function setRefreshToken($refreshToken = null)
    {
        $this->refreshToken = null === $refreshToken
            ? bin2hex(openssl_random_pseudo_bytes(64))
            : $refreshToken;

        return $this;
    }
}
