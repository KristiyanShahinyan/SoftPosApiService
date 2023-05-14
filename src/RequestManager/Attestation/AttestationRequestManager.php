<?php

namespace App\RequestManager\Attestation;

use App\Dto\Request\SecurityDto;
use App\Exception\ExceptionCodes;
use App\RequestManager\AbstractRequestManager;
use Chrisguitarguy\RequestId\RequestIdStorage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Cache\RedisCacheRequest;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AttestationRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'ATTESTATION_SERVICE';

    private RequestStack $stack;

    public function __construct(
        ClientInterface $client,
        RedisCacheRequest $cache,
        TokenStorageInterface $token,
        RequestIdStorage $storage,
        RequestStack $stack
    ) {
        parent::__construct($client, $cache, $token, $storage);
        $this->stack = $stack;
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function getChallenge($data)
    {
        return $this->post('/attestation/get-challenge', $data);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function verify($data)
    {
        return $this->post('/attestation/verify', $data);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function getSharedKey($data)
    {
        return $this->post('/attestation/get-shared-key', $data);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function setPinpadKey($data)
    {
        return $this->post('/attestation/set-pinpad-key', $data);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function setDetails($data)
    {
        return $this->post('/attestation/set-details', $data);
    }

    /**
     * @param SecurityDto $dto
     * @return mixed|ResponseInterface
     * @throws ApiException
     * @throws GuzzleException
     */
    public function decryptRequest(SecurityDto $dto)
    {
        return $this->post('/traffic/decrypt', $dto);
    }

    /**
     * @param SecurityDto $dto
     * @return mixed|ResponseInterface
     * @throws ApiException
     * @throws GuzzleException
     */
    public function encryptResponse(SecurityDto $dto)
    {
        return $this->post('/traffic/encrypt', $dto);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function safetynetGetNonce($data)
    {
        return $this->post('/safetynet/get-nonce', $data);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function safetynetVerify($data)
    {
        return $this->post('/safetynet/verify', $data);
    }

    /**
     * @param array $options
     * @throws ApiException
     */
    public function preRequest(array &$options): void
    {
        $request = $this->stack->getCurrentRequest();

        $expectedHeaders = [ 'x-device-id', 'x-app-type', 'x-api-version' ];

        foreach ($expectedHeaders as $headerName)
        {
            if (!$request->headers->has($headerName))
                throw new ApiException(ExceptionCodes::MISSING_REQUIRED_HEADER, [ $headerName ]);

            $this->_headers->add($headerName, $request->headers->get($headerName));
        }

        $this->_headers->add('x-api', 'api');

        parent::preRequest($options);

    }
}
