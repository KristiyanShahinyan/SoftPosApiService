<?php

namespace App\EventSubscriber;

use App\Helper\Utils;
use App\Service\CacheService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Phos\Exception\ApiException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class TokenValidatorSubscriber
 * @package App\EventSubscriber
 */
class TokenValidatorSubscriber implements EventSubscriberInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * TokenValidatorSubscriber constructor.
     * @param RequestStack $requestStack
     * @param CacheService $cacheService
     */
    public function __construct(RequestStack $requestStack, CacheService $cacheService)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->cacheService = $cacheService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_CREATED => 'onJWTCreated',
            Events::JWT_DECODED => 'onJWTDecoded',
        ];
    }

    /**
     * @param JWTCreatedEvent $event
     * @throws ApiException
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $data = $event->getData();
        $data['deviceId'] = $this->request->attributes->get('skip_device_id') ?
            $this->request->attributes->get('deviceId') :
            Utils::parseDeviceId($this->request);
        unset($data['roles']);

        $event->setData($data);
    }

    /**
     * @param JWTDecodedEvent $event
     */
    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();

        if (
            !array_key_exists('iat', $payload) ||
            !array_key_exists('username', $payload) ||
            !array_key_exists('deviceId', $payload)
        ) {
            $event->markAsInvalid();

            return;
        }

        $tokenTs = $payload['iat'];
        $usernameTs = $this->cacheService->getLoggedOutUserTs($payload['username']);

        if ($usernameTs !== false && $tokenTs < $usernameTs) {
            $event->markAsInvalid();

            return;
        }

        $deviceTs = $this->cacheService->getLoggedOutDeviceTs($payload['deviceId']);

        if ($deviceTs !== false && $tokenTs < $deviceTs) {
            $event->markAsInvalid();

            return;
        }

        // JWT token is still valid
    }
}
