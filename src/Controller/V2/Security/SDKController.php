<?php

namespace App\Controller\V2\Security;

use App\Dto\Request\SDKAuthenticateDto;
use App\Exception\ExceptionCodes;
use App\Helper\SdkAuthenticationHelper;
use App\Helper\Utils;
use App\Service\ExternalRequestService;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Controller\AbstractApiController;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SDKController extends AbstractApiController
{
    private ExternalRequestService $externalRequestService;

    private SdkAuthenticationHelper $sdkAuthenticationHelper;

    public function __construct(
        ExternalRequestService $externalRequestService,
        SdkAuthenticationHelper $sdkAuthenticationHelper
    )
    {
        $this->externalRequestService = $externalRequestService;
        $this->sdkAuthenticationHelper = $sdkAuthenticationHelper;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function authenticate(Request $request): JsonResponse
    {
        /** @var SDKAuthenticateDto $dto */
        $dto = $this->deserialize($request->getContent(), SDKAuthenticateDto::class);
        $this->validate($dto);
        $deviceId = Utils::parseDeviceId($request);

        $token = $this->sdkAuthenticationHelper->specialAuthUser($dto->getIssuer(), $deviceId, true);
        if (!is_null($token))
            return $this->success($token);

        $token = $this->externalRequestService->getUserData($dto, $dto->getIssuer(), $deviceId, true);
        if (!$token){
            throw new ApiException(ExceptionCodes::SDK_INVALID_ISSUER, ['The issuer is not registered in Phos']);
        }
        return $this->success($token);
    }
}