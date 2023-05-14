<?php

namespace App\EventSubscriber;

use Phos\Exception\ApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiKernelExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RequestResponseLoggerSubscriber constructor.
     * @param LoggerInterface $communicationLogLogger
     */
    public function __construct(LoggerInterface $communicationLogLogger)
    {
        $this->logger = $communicationLogLogger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();
        $headers = $request->headers->all();
        $content = $request->getContent();


        if(!$exception instanceof ApiException)
        {
            $log = [
                'service'           => 'pos',
                'request.method'    => $request->getMethod(),
                'request.headers'   => json_encode($headers),
                'request.content'   => json_encode($content),
                'request.path'      => $request->getPathInfo(),
                'request.ip'        => $request->getClientIp(),
                'request.device_id' => $request->headers->get('x-device-id'),
                'request.app'       => $request->headers->get('x-app-type')
            ];

            $this->logger->info('Request', $log);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
