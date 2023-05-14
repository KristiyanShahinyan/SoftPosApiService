<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;

/**
 * Class NotificationServiceReceiptSendDto
 * @package App\Dto
 */
class NotificationServiceReceiptSendDto implements DtoInterface
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
     * @var string
     */
    protected $recipient;

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
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

}
