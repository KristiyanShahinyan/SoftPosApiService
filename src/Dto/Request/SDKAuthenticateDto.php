<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SDKAuthenticateDto
 * @package App\Dto\Request
 */
class SDKAuthenticateDto implements DtoInterface
{
    /**
     * @Assert\NotBlank()
     */
    protected $issuer;

    /**
     * @Assert\NotBlank()
     */
    protected $token;

    /**
     */
    protected $license;

    /**
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * @param string $issuer
     */
    public function setIssuer($issuer): void
    {
        $this->issuer = $issuer;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param string
     */
    public function setLicense($license): void
    {
        $this->license = $license;
    }
}
