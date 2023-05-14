<?php

namespace App\Service;

use App\Dto\TransactionDto;
use App\Message\TransactionCheckMessage;
use App\RequestManager\Monitoring\MonitoringRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Symfony\Component\Messenger\MessageBusInterface;

class MonitoringService
{
    private const HIGH_SEVERITY = 3;
    private const MID_SEVERITY = 2;
    private const LOW_SEVERITY = 1;
    private const MESSAGE = 'messages';
    private const SUCCESS = 'success';

    private MonitoringRequestManager $requestManager;

    private array $response;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus, MonitoringRequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
        $this->messageBus = $messageBus;
    }


    /**
     * @param TransactionDto $trnDto
     * @return self
     * @throws GuzzleException
     * @throws ApiException
     */
    public function highSeverityMonitoring(TransactionDto $trnDto): self
    {
        $this->response = $this->requestManager->checkTransaction($trnDto, [self::HIGH_SEVERITY]);

        return $this;
    }

    public function midSeverityMonitoring(TransactionDto $trnDto)
    {
        $this->messageBus->dispatch($this->buildMessage($trnDto, [self::MID_SEVERITY]));
    }

    public function lowSeverityMonitoring(TransactionDto $trnDto)
    {
        $this->messageBus->dispatch($this->buildMessage($trnDto, [self::LOW_SEVERITY, self::MID_SEVERITY]));
    }

    public function passes(): bool
    {
        return $this->response[self::SUCCESS];
    }

    public function getErrors(): array
    {
        return $this->response[self::MESSAGE];
    }

    private function buildMessage(TransactionDto $dto, array $severity): TransactionCheckMessage
    {
        $message = new TransactionCheckMessage();
        $message->setPayload($dto);
        $message->setSeverity($severity);

        return $message;
    }

}