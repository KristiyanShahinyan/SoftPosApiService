<?php

namespace App\EventSubscriber;

use App\Entity\Random;
use App\Exception\ExceptionCodes;
use App\Helper\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Phos\Cache\RedisCacheRequest;
use Phos\Exception\ApiException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AntiReplySubscriber implements EventSubscriberInterface
{
    protected EntityManagerInterface $em;
    private RedisCacheRequest $cache;

    public function __construct(RedisCacheRequest $cache, EntityManagerInterface $em)
    {
        $this->cache = $cache;
        $this->em = $em;
    }

    public function onKernelRequestCheckForReply(KernelEvent $event)
    {
	    return;
        $request = $event->getRequest();
        if ($request->isMethod('POST') && Utils::parseAppVersion($request) > 10010) {
            $requestContentJson = $request->getContent();

            $arp = $this->getArpFromContent($requestContentJson);

            $this->checkTheRandom($arp);

            $hash = hash('sha256', $requestContentJson);
            
            $this->checkTheHash($hash);
        }
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST  => ['onKernelRequestCheckForReply', 9],
        ];
    }

    private function getArpFromContent(string $requestContentJson): string
    {
        $content = json_decode($requestContentJson, true);
       // if (!isset($content['arp'])) {
       //     throw new ApiException(\Phos\Exception\ExceptionCodes::HTTP_BAD_REQUEST);
       // }

        return $content['arp'];
    }

    private function checkTheRandom(string $arp): void
    {
        $base64EncodedRandom = base64_encode(hex2bin(substr($arp, 0, 64)));

        /** @var Random|null $random */
        $random = $this->em->getRepository(Random::class)->findOneNotExpired($base64EncodedRandom);
        if ($random === null) {
            throw new ApiException(ExceptionCodes::REPLY_REQUEST);
        }
    }

    private function checkTheHash(string $hash): void
    {
        $hasSameRequest = $this->cache->get($hash, null, false);
        if ($hasSameRequest) {
            throw new ApiException(ExceptionCodes::REPLY_REQUEST);
        }

        $this->cache->set($hash, $hash);
    }
}