<?php


namespace App\EventSubscriber;


use App\RequestManager\Security\SecurityRequestManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DeviceRequestValidatorSubscriber implements EventSubscriberInterface
{
    private SecurityRequestManager $requestManager;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    private const DEVICE_SIGN_KEY = 'x-signature-key-alias';

    public function __construct(SecurityRequestManager $requestManager,    ContainerInterface $container)
    {
        $this->requestManager = $requestManager;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST  => ['validateApkSignature', 15],
        ];
    }

    public function validateApkSignature(KernelEvent $event)
    {
        $isOlderVersion = ((int) $event->getRequest()->headers->get('x-app-version')) < 10011;

        //If REQUEST_VERIFICATION is not yes, skip
        if ($this->container->getParameter('REQUEST_VERIFICATION') !== 'yes' || $isOlderVersion) {

            return;
        }

        $headers= $event->getRequest()->headers;
        $body = $event->getRequest()->getContent();
        $usingApkKey =  $headers->get(self::DEVICE_SIGN_KEY, false)  && $headers->get(self::DEVICE_SIGN_KEY) !== 'phos-device-sign-key';

        $this->requestManager->verifyDeviceRequest(compact('headers', 'body', 'usingApkKey'));
    }

}