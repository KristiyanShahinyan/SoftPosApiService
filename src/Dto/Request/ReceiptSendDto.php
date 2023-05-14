<?php
/**
 * Created by PhpStorm.
 * User: dian
 * Date: 3.08.18
 * Time: 10:52
 */

namespace App\Dto\Request;

use App\Dto\DtoInterface;

/**
 * Class ReceiptDto
 * @package App\Dto\Request
 */
class ReceiptSendDto implements DtoInterface
{
    /**
     * @var string
     */
    protected $transactionKey;

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
     * @return string
     */
    public function getTransactionKey(): string
    {
        return $this->transactionKey;
    }

    /**
     * @param string $transactionKey
     */
    public function setTransactionKey(string $transactionKey): void
    {
        $this->transactionKey = $transactionKey;
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
