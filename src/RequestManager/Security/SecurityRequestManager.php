<?php

namespace App\RequestManager\Security;

use App\Builder\SecurityBuilder;
use App\Dto\Request\PinInitializationDto;
use App\Dto\Request\SecurityDto;
use App\Exception\ExceptionCodes;
use App\RequestManager\AbstractRequestManager;
use Chrisguitarguy\RequestId\RequestIdStorage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Phos\Cache\RedisCacheRequest;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'SECURITY_SERVICE';

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
    public function generate($data)
    {
        return $this->post('/shared-key/generate', $data);
    }

    /**
     * @param $request
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     */
    public function renew($request)
    {
        $builder = new SecurityBuilder();

        $dto = $builder->build($request);

        return $this->post('/shared-key/renew', $dto);
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function setPinpadKey($data)
    {
        return $this->post('/pin/set-pinpad-key', $data);
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
     * @param string $deviceId
     * @param int $page
     * @param int $limit
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findDeviceById(string $deviceId, int $page = 1, int $limit = 1)
    {
        $groups = 'index';
        return $this->get('/devices/list/{page}/{limit}', compact('page', 'limit'), compact('deviceId', 'groups'));
    }

    /**
     * @param string $deviceId
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getOrCreateDevice(string $deviceId)
    {
        return $this->get('/devices/get-or-create/{deviceId}', compact('deviceId'));
    }

    /**
     * @param $data
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     */
    public function createDetails($data)
    {
        return $this->post('/details/create', $data);
    }

    /**
     * @param array $requestInformation
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     */
    public function verifyDeviceRequest(array $requestInformation)
    {
        return $this->post('/device/verifyRequest', $requestInformation);
    }

    /**
     * @param string $publicKey
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     */
    public function createDeviceVerificationKey(string $publicKey)
    {
        return $this->post('/device/createDeviceVerificationPublicKey', $publicKey);
    }

    /**
     * @param PinInitializationDto $dto
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     */
    public function pinInit(PinInitializationDto $dto)
    {
        $request = $this->stack->getCurrentRequest();
        $this->addRequiredHeaders(['x-app-version', 'x-package-name']);
        if ($request->headers->has('x-sdk-version'))
            $this->_headers->add('x-sdk-version', $request->headers->get('x-sdk-version'));

        return $this->post('/pin/init', $dto);
    }

    /**
     * @param array $options
     * @throws ApiException
     */
    public function preRequest(array &$options): void
    {
        $expectedHeaders = [ 'x-device-id', 'x-app-type', 'x-api-version' ];

        $this->addRequiredHeaders($expectedHeaders);

        $this->_headers->add('x-api', 'api');

        parent::preRequest($options);

    }

    public function findKmsKey(): array
    {
        return array('key' => getenv('KMS_KEY_ID'));
    }

    /**
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sessionInit() {
        $this->addRequiredHeaders(['x-package-name', 'x-timestamp']);

        return $this->post('/shared-key/session-init');
    }

    public function sessionPing($request){

        $builder = new SecurityBuilder();
        $dto = $builder->build($request);
        return $this->post('/shared-key/session-ping', $dto);
    }

    /**
     * @param array $headers
     * @return void
     * @throws ApiException
     */
    private function addRequiredHeaders(array $headers): void
    {
        $request = $this->stack->getCurrentRequest();

        foreach ($headers as $headerName)
        {
            if (!$request->headers->has($headerName))
                throw new ApiException(ExceptionCodes::MISSING_REQUIRED_HEADER, [ $headerName ]);

            $this->_headers->add($headerName, $request->headers->get($headerName));
        }
    }
}
