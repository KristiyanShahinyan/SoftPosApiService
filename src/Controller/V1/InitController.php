<?php

namespace App\Controller\V1;

use App\Dto\Request\PinInitializationDto;
use App\Dto\Response\InstanceFeaturesConfigurationsDto;
use App\Dto\Response\InstanceSkinConfigurationDto;
use App\Exception\ExceptionCodes;
use App\Helper\Utils;
use App\RequestManager\Account\InstanceRequestManager;
use App\RequestManager\Attestation\AttestationRequestManager;
use App\RequestManager\Security\SecurityRequestManager;
use App\Service\AppUpdateService;
use App\Service\DeviceService;
use App\Service\RandomService;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use LogicException;
use Phos\Controller\AbstractApiController;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InitController extends AbstractApiController
{
    private InstanceRequestManager $instanceRequestManager;

    private AppUpdateService $appUpdateService;

    private RandomService $randomService;

    private DeviceService $deviceService;

    private AttestationRequestManager $attestationRequestManager;

    private SecurityRequestManager $securityRequestManager;

    public function __construct(
        InstanceRequestManager $instanceRequestManager,
        AppUpdateService $appUpdateService,
        RandomService $randomService,
        DeviceService $deviceService,
        AttestationRequestManager $attestationRequestManager,
        SecurityRequestManager $securityRequestManager
    )
    {
        $this->instanceRequestManager = $instanceRequestManager;
        $this->appUpdateService = $appUpdateService;
        $this->randomService = $randomService;
        $this->deviceService = $deviceService;
        $this->attestationRequestManager = $attestationRequestManager;
        $this->securityRequestManager = $securityRequestManager;
    }

    public function currentTime(): JsonResponse
    {
        return $this->success(['date' => $this->getCurrentTime()]);
    }

    public function getInstanceToken(string $alias)
    {
        $instance = $this->instanceRequestManager->findByAlias($alias, 'token');

        return $this->success(['instance_token' => $instance['token']]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return JsonResponse|Response
     * @throws ApiException
     */
    public function init(Request $request, string $name)
    {
        return $this->success($this->init_instance($request, $name));
    }

    /**
     * @throws ApiException|GuzzleException
     */
    public function combined(Request $request, string $name, int $bytes = 32)
    {
        $result = $this->init_instance($request, $name);
        $result['date'] = $this->getCurrentTime();
        $result['random'] = $this->randomService->generateRandom($bytes);

        $device = $this->deviceService->getDevice($request);
        $nonce = $this->attestationRequestManager->safetynetGetNonce($device['device_id']);
        $result['nonce'] = $nonce['nonce'];

        return $this->success($result);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ApiException
     * @throws GuzzleException
     */
    public function pinInitialization(Request $request)
    {
        /** @var PinInitializationDto $dto */
        $dto = $this->deserialize($request->getContent(), PinInitializationDto::class);

        $this->validate($dto);

        $this->securityRequestManager->pinInit($dto);

        return $this->success();
    }

    /**
     * @param $request
     * @param $name
     * @return array
     * @throws ApiException
     */
    private function init_instance($request, $name): array
    {
        try {
            $instance = $this->instanceRequestManager->findByAlias($name, 'configuration');
        } catch (Throwable $e) {
            throw new ApiException(ExceptionCodes::INSTANCE_NOT_FOUND);
        }

        $configurations = array_column($instance['configurations'] ?? [], 'value', 'key');
        $features = $this->deserialize($configurations['features'] ?? '', InstanceFeaturesConfigurationsDto::class);
        $skin = $this->deserialize($configurations['skin'] ?? '', InstanceSkinConfigurationDto::class);

        try {
            $update = $this->appUpdateService->getUpdate($instance['id'], Utils::parseAppVersion($request));
        } catch (LogicException $e) {
            throw new ApiException(ExceptionCodes::UNRECOGNIZED_APP_VERSION, [$e->getMessage()]);
        }

        return compact('features', 'skin', 'update');
    }

    private function getCurrentTime(): string
    {
        return (new DateTime())->format('Y-m-d H:i:s');
    }
}
