<?php

namespace App\Service;

use App\Dto\Request\SDKAuthenticateDto;
use App\Helper\SdkAuthenticationHelper;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Phos\Exception\ApiException;
use Phos\Exception\ExceptionCodes;
use Phos\Helper\SerializationTrait;
use Psr\Http\Message\ResponseInterface;

class ExternalRequestService
{
    use SerializationTrait;
    
    private ClientInterface $client;

    private SdkAuthenticationHelper $sdkAuthenticationHelper;

    public function __construct(ClientInterface $client, SdkAuthenticationHelper $sdkAuthenticationHelper)
    {
        $this->client = $client;
        $this->sdkAuthenticationHelper = $sdkAuthenticationHelper;
    }

    /**
     * @param SDKAuthenticateDto $dto
     * @param string $issuer
     * @param string $deviceId
     * @param bool $withRefreshToken
     * @return string|array
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getUserData(SDKAuthenticateDto $dto, string $issuer, string $deviceId, bool $withRefreshToken = false)
    {
        $data = [
            'token' => $dto->getToken(),
            'pre-shared-key' => getenv('SDK_PRE_SHARED_KEY_' . strtoupper($issuer))
        ];

        $response = $this->request('post', getenv('SDK_AUTHENTICATION_URL_' . strtoupper($issuer)), $data);

        if (empty($response['user-id'])) {
            throw new ApiException(ExceptionCodes::GENERAL, ['Invalid client response']);
        }
        $token = $this->sdkAuthenticationHelper->getToken($response['user-id'], $deviceId);

        return $withRefreshToken ? $token : $token['token'];
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     *
     * @return mixed|ResponseInterface
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    private function request(string $method, string $uri, array $data)
    {
        $data = json_encode($data);

        $options = [
            RequestOptions::BODY => $data,
            RequestOptions::HEADERS => ['Content-Type' => 'application/json']
        ];

        $response = $this->client->request($method, $uri, $options);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
