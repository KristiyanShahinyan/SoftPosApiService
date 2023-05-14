<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PinInitializationDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private PinInitializationKeysDto $keys;

    /**
     * @Assert\NotBlank()
     */
    private string $pinInitSignature;

    /**
     * @return PinInitializationKeysDto
     */
    public function getKeys(): PinInitializationKeysDto
    {
        return $this->keys;
    }

    /**
     * @param PinInitializationKeysDto $keys
     */
    public function setKeys(PinInitializationKeysDto $keys): void
    {
        $this->keys = $keys;
    }

    /**
     * @return string
     */
    public function getPinInitSignature(): string
    {
        return $this->pinInitSignature;
    }

    /**
     * @param string $pinInitSignature
     */
    public function setPinInitSignature(string $pinInitSignature): void
    {
        $this->pinInitSignature = $pinInitSignature;
    }
}