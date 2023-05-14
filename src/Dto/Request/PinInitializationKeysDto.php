<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PinInitializationKeysDto
{
    /**
     * @Assert\NotBlank()
     */
    private string $pinKeyWrappingKey;

    /**
     * @Assert\NotBlank()
     */
    private string $pinPayloadSigningKey;

    /**
     * @return string
     */
    public function getPinKeyWrappingKey(): string
    {
        return $this->pinKeyWrappingKey;
    }

    /**
     * @param string $pinKeyWrappingKey
     */
    public function setPinKeyWrappingKey(string $pinKeyWrappingKey): void
    {
        $this->pinKeyWrappingKey = $pinKeyWrappingKey;
    }

    /**
     * @return string
     */
    public function getPinPayloadSigningKey(): string
    {
        return $this->pinPayloadSigningKey;
    }

    /**
     * @param string $pinPayloadSigningKey
     */
    public function setPinPayloadSigningKey(string $pinPayloadSigningKey): void
    {
        $this->pinPayloadSigningKey = $pinPayloadSigningKey;
    }
}