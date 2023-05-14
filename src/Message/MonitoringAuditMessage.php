<?php


namespace App\Message;


class MonitoringAuditMessage
{
    private array $requestHeaders;

    private $payload;

    private bool $isBulk = false;

    /**
     * @return array
     */
    public function getRequestHeaders(): array
    {
        return $this->requestHeaders;
    }

    /**
     * @param array $requestHeaders
     */
    public function setRequestHeaders(array $requestHeaders): void
    {
        $this->requestHeaders = $requestHeaders;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    public function getIsBulk(): bool
    {
        return $this->isBulk;
    }

    public function setIsBulk(bool $isBulk): void
    {
        $this->isBulk = $isBulk;
    }
}