<?php

namespace App\RequestManager;

use Chrisguitarguy\RequestId\RequestIdStorage;
use GuzzleHttp\ClientInterface;
use Phos\Cache\RedisCacheRequest;
use Phos\HttpRequest\ApiHttpRequestService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractRequestManager
 * @package App\RequestManager
 */
abstract class AbstractRequestManager extends ApiHttpRequestService
{
    /**
     * @var RequestIdStorage
     */
    private $storage;

    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * AbstractRequestManager constructor.
     * @param ClientInterface $client
     * @param RedisCacheRequest $cache
     * @param TokenStorageInterface $token
     * @param RequestIdStorage $storage
     */
    public function __construct(ClientInterface $client,
                                RedisCacheRequest $cache,
                                TokenStorageInterface $token,
                                RequestIdStorage $storage)
    {
        parent::__construct($client, $cache);
        $this->storage = $storage;
        $this->token = $token;
    }

    /**
     * Add channel and request id
     * @param array $options
     */
    public function preRequest(array &$options): void
    {
        $this->_headers->add('X-Channel', 'mobile');
        $this->_headers->add('X-Request-Id', $this->storage->getRequestId());

        $token = $this->token->getToken();

        if ($token && $token->getUser() instanceof UserInterface) {
            $this->_headers->add('X-User', $token->getUser()->getToken());
        }
    }
}
