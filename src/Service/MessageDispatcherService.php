<?php


namespace App\Service;


use App\Message\MonitoringAuditMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageDispatcherService
{

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @return Envelope
     * @param array $content
     * @param mixed $headers
     */
    public function logMessage(array $headers, $content = [], bool $isBulk = false)
    {
        $message = new MonitoringAuditMessage();
        $message->setPayload($content);
        $message->setRequestHeaders($headers);
        $message->setIsBulk($isBulk);

        return $this->messageBus->dispatch($message);
    }

}