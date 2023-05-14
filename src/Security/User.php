<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

/**
 * Class User
 * @package App\Security\Phos
 */
class User implements JWTUserInterface
{

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $username = '';

    /**
     * @var string
     */
    private $email;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var string
     */
    private $token;

    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @var bool
     */
    private $isVerified = true;

    /**
     * @var Merchants[]|null
     */
    private $merchants;

    /**
     * @var string|null
     */
    private $currentMerchantToken;

    /**
     * @param string $username
     * @param array $payload
     * @return JWTUserInterface|void
     */
    public static function createFromPayload($username, array $payload)
    {
    }

    /**
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
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     */
    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // do nothing
    }

    /**
     * @return Merchants[]|null
     */
    public function getMerchants(): ?array
    {
        return $this->merchants;
    }

    /**
     * @param Merchants[]|null $merchants
     */
    public function setMerchants(?array $merchants): void
    {
        $this->merchants = $merchants;
    }

    public function addMerchants(Merchants $merchants)
    {
        $this->merchants[] = $merchants;
    }

    /**
     * @return mixed
     */
    public function getCurrentMerchantToken()
    {
        return $this->currentMerchantToken;
    }

    /**
     * @param mixed $currentMerchantToken
     */
    public function setCurrentMerchantToken($currentMerchantToken): void
    {
        $this->currentMerchantToken = $currentMerchantToken;
    }

    public function getUserIdentifier(): string
    {
        return $this->token ?? '';
    }
}
