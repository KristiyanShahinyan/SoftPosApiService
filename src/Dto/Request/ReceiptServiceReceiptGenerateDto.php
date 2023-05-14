<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;

/**
 * Class GenerateReceiptDto
 * @package App\Dto\Request
 */
class ReceiptServiceReceiptGenerateDto implements DtoInterface
{
    /**
     * @var array
     */
    protected $transaction;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $timeZone;

    /**
     * @var string|null
     */
    protected $format;

    /**
     * @return array
     */
    public function getTransaction(): array
    {
        return $this->transaction;
    }

    /**
     * @param array $transaction
     */
    public function setTransaction(array $transaction): void
    {
        $this->transaction = $transaction;
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
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     */
    public function setTimeZone(string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @param string|null $format
     */
    public function setFormat(?string $format): void
    {
        $this->format = $format;
    }
}
