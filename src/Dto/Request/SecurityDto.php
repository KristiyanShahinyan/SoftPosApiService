<?php

namespace App\Dto\Request;

use App\Dto\SecurityInterface;

/**
 * Class DecryptRequestDto
 * @package App\Dto\Request
 */
class SecurityDto implements SecurityInterface
{

    /**
     * @var string
     */
    private $data;

    /**
     * @var bool|null
     */
    private $renew;

    /**
     * @var bool|null
     */
    private $encrypt;

    /**
     * @var bool|null
     */
    private $compress = false;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return bool|null
     */
    public function getRenew(): ?bool
    {
        return $this->renew;
    }

    /**
     * @param bool|null $renew
     */
    public function setRenew(?bool $renew): void
    {
        $this->renew = $renew;
    }

    /**
     * @return bool|null
     */
    public function getEncrypt(): ?bool
    {
        return $this->encrypt;
    }

    /**
     * @param bool|null $encrypt
     */
    public function setEncrypt(?bool $encrypt): void
    {
        $this->encrypt = $encrypt;
    }

    /**
     * @return bool|null
     */
    public function getCompress(): ?bool
    {
        return $this->compress;
    }

    /**
     * @param bool|null $compress
     */
    public function setCompress(?bool $compress): void
    {
        $this->compress = $compress;
    }

}
