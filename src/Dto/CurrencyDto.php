<?php

namespace App\Dto;

class CurrencyDto
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $code;

    /**
     * @var string|null
     */
    private $symbol;

    /**
     * @var int|null
     */
    private $fractionDigits;

    /**
     * @var int|null
     */
    private $roundingIncrement;

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
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    /**
     * @param string|null $symbol
     */
    public function setSymbol(?string $symbol): void
    {
        $this->symbol = $symbol;
    }

    /**
     * @return int|null
     */
    public function getFractionDigits(): ?int
    {
        return $this->fractionDigits;
    }

    /**
     * @param int|null $fractionDigits
     */
    public function setFractionDigits(?int $fractionDigits): void
    {
        $this->fractionDigits = $fractionDigits;
    }

    /**
     * @return int|null
     */
    public function getRoundingIncrement(): ?int
    {
        return $this->roundingIncrement;
    }

    /**
     * @param int|null $roundingIncrement
     */
    public function setRoundingIncrement(?int $roundingIncrement): void
    {
        $this->roundingIncrement = $roundingIncrement;
    }
}