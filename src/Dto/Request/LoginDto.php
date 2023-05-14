<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginDto
 * @package App\Dto\Request
 */
class LoginDto implements DtoInterface
{
    /**
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * @Assert\NotBlank(message="Password is required!")
     */
    protected $password;

    /**
     * @var mixed
     */
    protected $instance;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $deviceId;


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed|null
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param mixed|null $instance
     */
    public function setInstance($instance): void
    {
        $this->instance = $instance;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    /**
     * @param string|null $deviceId
     */
    public function setDeviceId(?string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }



}
