<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Throwable;

/**
 * Class RequestResponseLoggerSubscriber
 * @package App\EventSubscriber
 */
class RequestResponseLoggerSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * RequestResponseLoggerSubscriber constructor.
     * @param LoggerInterface $communicationLogLogger
     * @param RouterInterface $router
     */
    public function __construct(LoggerInterface $communicationLogLogger, RouterInterface $router)
    {
        $this->logger = $communicationLogLogger;
        $this->router = $router;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents(): array
    {

        if (getenv('ENABLE_LOGGER') === 'no')
            return [];

        return [
            'kernel.request'  => ['onKernelRequest', 11],
            'kernel.response' => 'onKernelResponse',
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        try {
            $request = $event->getRequest();

            $content = json_decode($request->getContent(), true);

            //Obfuscate sensitive request data
            isset($content['certificates']) ? $content['certificates'] = str_repeat('x', 10) : null;
            isset($content['password']) ? $content['password'] = str_repeat('x', strlen($content['password'])) : null;
            isset($content['old_password']) ? $content['old_password'] = str_repeat('x', strlen($content['old_password'])) : null;
            isset($content['new_password']) ? $content['new_password'] = str_repeat('x', strlen($content['new_password'])) : null;

            if ($this->router->match($request->getPathInfo()) === 'api_v1_safetynet_verify' || $this->router->match($request->getPathInfo()) === 'api_v2_safetynet_verify') {
                isset($content['data']) ? $content['data'] = str_repeat('x', 10) : null;
            }

            //Obfuscate sensitive headers data
            $headers = $request->headers->all();
            isset($headers['authorization']) ? $headers['authorization'] = str_repeat('x', 10) : null;

            $log = [
                'service'           => 'api',
                'request.method'    => $request->getMethod(),
                'request.headers'   => json_encode($headers),
                'request.content'   => json_encode($content),
                'request.path'      => $request->getPathInfo(),
                'request.ip'        => $request->getClientIp(),
                'request.device_id' => $request->headers->get('x-device-id'),
                'request.app'       => $request->headers->get('x-app-type')
            ];

            $this->logger->info('Request', $log);
        } catch (Throwable $e) {

        }

    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        try {
            $request = $event->getRequest();
            $response = $event->getResponse();

            $content = json_decode($response->getContent(), true);

            isset($content['refresh_token']) ? $content['refresh_token'] = str_repeat('x', 10) : null;
            isset($content['token']) ? $content['token'] = str_repeat('x', 10) : null;
            isset($content['nonce']) ? $content['nonce'] = str_repeat('x', 10) : null;
            isset($content['shared_key']) ? $content['shared_key'] = str_repeat('x', 10) : null;

            $log = [
                'service'            => 'api',
                'response.content'   => json_encode($content),
                'response.path'      => $request->getPathInfo(),
                'response.ip'        => $request->getClientIp(),
                'response.device_id' => $request->headers->get('x-device-id'),
                'response.app'       => $request->headers->get('x-app-type')
            ];

            $this->logger->info('Response', $log);
        } catch (Throwable $e) {

        }
    }
}
