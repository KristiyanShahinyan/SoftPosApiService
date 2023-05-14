<?php

namespace App\EventSubscriber;

use App\Builder\SecurityBuilder;
use App\Exception\ExceptionCodes;
use App\RequestManager\Security\SecurityRequestManager;
use Aws\Kms\KmsClient;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EncryptedRequestSubscriber
 * @package App\EventSubscriber
 */
class EncryptedRequestSubscriber implements EventSubscriberInterface
{
    // Request ATTRIBUTES
    public const ATTR_ENCRYPTED = 'encrypted';

    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var SecurityRequestManager
     */
    protected SecurityRequestManager $securityManager;

    /**
     * @var SecurityBuilder
     */
    private SecurityBuilder $builder;

    /**
     * @var KmsClient
     */
    private KmsClient $kmsClient;

    /**
     * EncryptedRequestSubscriber constructor.
     *
     * @param SecurityBuilder $builder
     * @param ContainerInterface $container
     * @param KmsClient $kmsClient
     * @param SecurityRequestManager $securityManager
     */
    public function __construct(SecurityBuilder $builder,
                                ContainerInterface $container,
                                KmsClient $kmsClient,
                                SecurityRequestManager $securityManager)
    {
        $this->builder = $builder;
        $this->container = $container;
        $this->securityManager = $securityManager;
        $this->kmsClient = $kmsClient;
    }

    /**
     * @todo Separate in different method for each event
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST  => ['onKernelRequestResponseDecryptEncrypt', 12],
            KernelEvents::RESPONSE => 'onKernelRequestResponseDecryptEncrypt',
        ];
    }

    /**
     * @param RequestEvent|ResponseEvent|KernelEvent $event
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function onKernelRequestResponseDecryptEncrypt(KernelEvent $event): void
    {
        $compress = $event->getRequest()->headers->get('x-compress');
        if (empty($compress)) {
            $compress = 'false';
            $shouldCompress = false;
        } else {
            $shouldCompress = true;
        }

        //If sign response
        if ($event instanceof ResponseEvent && $event->getRequest()->attributes->get('signed')) {
            $this->sign($event->getResponse(), $shouldCompress);
        }

        $isRoutedEncrypted = $event->getRequest()->attributes->get(static::ATTR_ENCRYPTED);

        $isEncryptionEnabled = $this->container->getParameter('ENCRYPTION');
        $shouldEncrypt = ($isRoutedEncrypted === true && $isEncryptionEnabled === 'yes');

        if (!$shouldCompress && !$shouldEncrypt) {
            return;
        }

        //If the event is response encrypt request
        if ($event instanceof ResponseEvent) {
            $event->getResponse()->headers->set('x-compress', $compress, true);

            if ($shouldCompress && !$shouldEncrypt) {
                $response = $event->getResponse();
                $response->setContent($this->compress($response->getContent()));
            } elseif ($shouldEncrypt) {
                $this->encryptResponse($event, $shouldCompress);

                $event->getResponse()->headers->add(['X-Timestamp' => time()]);
                $event->getResponse()->headers->add(['X-Key' => time()]);

                return;
            }
        } else {
            if ($shouldCompress && !$shouldEncrypt && $event->getRequest()->getMethod() !== 'GET') {
                $request = $event->getRequest();
                $uncompressed = $this->uncompress($request->getContent());
                $headers = $request->headers->all();
                $request->initialize(
                    $request->query->all(),
                    $request->request->all(),
                    $request->attributes->all(),
                    $request->cookies->all(),
                    $request->files->all(),
                    $request->server->all(),
                    $uncompressed
                );
                $request->headers->replace($headers);
            } elseif ($shouldEncrypt) {
                //Else decrypt response
                $this->decryptRequest($event, $shouldCompress);
            }
        }
    }

    /**
     * @param Response $response
     * @param bool $compress
     */
    protected function sign(Response $response, bool $compress = false)
    {
        $signature = base64_encode($this->kmsClient->sign(
            ['KeyId'            => $this->securityManager->findKmsKey()['key'],
                'Message'          => openssl_digest($response->getContent(), 'sha256', true),
                'SigningAlgorithm' => 'RSASSA_PKCS1_V1_5_SHA_256',
                'MessageType'      => 'DIGEST'])['Signature']);
        $response->headers->add(['X-Signature' => $signature]);
    }

    /**
     * @param ResponseEvent $event
     * @param bool $compress
     * @return void
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    protected function encryptResponse(ResponseEvent $event, bool $compress = false): void
    {
        $response = $event->getResponse();

        $content = $response->getContent();
        if ($compress) {
            $content = $this->compress($content);
        }

        //Build dto for security service.(device_id,api_version,app_type and data)
        $dto = $this->builder->build($content, $compress);

        try {
            //Try encrypt response
            $encryptedResponse = $this->securityManager->encryptResponse($dto);
        } catch (Exception $e) {
            throw new ApiException(ExceptionCodes::UNABLE_TO_ENCRYPT_DATA);
        }

        //Replace response content
        $response->setContent($encryptedResponse['content']);
    }

    /**
     * @param RequestEvent $event
     * @throws ApiException
     * @throws GuzzleException
     */
    protected function decryptRequest(RequestEvent $event, bool $compress = false): void
    {
        //Get request
        $request = $event->getRequest();
        //Get content. Will be read if steam by default
        $content = $request->getContent();

        //If no content return
        if ($content === '') {
            // nothing to decrypt
            return;
        }

        //Build dto for security service.(device_id,api_version,app_type and data)
        $dto = $this->builder->build($content, $compress);
        //Try decrypt request
        try {
            $decrypted = $this->securityManager->decryptRequest($dto)['content'];
        } catch (Exception $e) {
            // TODO: FIXME
            $message = $e->getMessage();
            if (strpos($message, 'Error decrypting request: Exception: Failed decrypting the data ') === 0) {
                throw new ApiException(ExceptionCodes::ENCRYPTION_KEY_NOT_FOUND);
            }
            throw new ApiException(ExceptionCodes::UNABLE_TO_DECRYPT_DATA);
        }

        if ($compress) {
            $decrypted = $this->uncompress($decrypted);
        }

        //Get headers
        $headers = $request->headers->all();

        //Re-init request with decrypt content
        $request->initialize(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $decrypted
        );

        //Set cached headers
        $request->headers->replace($headers);

    }

    /**
     * TODO: verify with device public key
     */
    protected function verify()
    {

    }

    private function compress(string $content, bool $encode = true): string
    {
        return $encode ? base64_encode(gzencode($content, 9)) : gzencode($content, 9);
    }

    private function uncompress(string $content): string
    {
        return gzdecode(base64_decode($content));
    }

}
