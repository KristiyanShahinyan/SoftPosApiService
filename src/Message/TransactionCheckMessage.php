<?php

namespace App\Message;

use App\Dto\TransactionDto;

class TransactionCheckMessage
{
    private TransactionDto $payload;

    private array $severity;

    public function getPayload(): TransactionDto
    {
        return $this->payload;
    }

    public function setPayload(TransactionDto $payload): void
    {
        $this->payload = $payload;
    }

    public function getSeverity(): array
    {
        return $this->severity;
    }

    public function setSeverity(array $severity): void
    {
        $this->severity = $severity;
    }
}