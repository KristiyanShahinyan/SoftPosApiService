<?php

namespace App\Controller\V1;

use App\Dto\Request\AttestationDetailsDto;
use App\Helper\Utils;
use App\RequestManager\Attestation\AttestationRequestManager;
use App\RequestManager\Security\SecurityRequestManager;
use App\Service\DeviceService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Phos\Helper\LoggerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AttestationController extends BaseController
{
    use LoggerTrait;

    protected AttestationRequestManager $attestationRequestManager;

    private SecurityRequestManager $securityRequestManager;

    private DeviceService $deviceService;

    public function __construct(AttestationRequestManager $manager, SecurityRequestManager $securityRequestManager, DeviceService $deviceService)
    {
        $this->attestationRequestManager = $manager;
        $this->securityRequestManager = $securityRequestManager;
        $this->deviceService = $deviceService;
    }

    /**
     * @throws ApiException
     * @throws GuzzleException
     */
    public function init(Request $request): JsonResponse
    {
        $attestationDetailsDto = new AttestationDetailsDto();
        $attestationDetailsDto->setDetails(json_decode($request->getContent(), true));

        $details = $attestationDetailsDto->getDetails();

        $deviceId = Utils::parseDeviceId($request);
        $this->securityRequestManager->getOrCreateDevice($deviceId);

        if (!empty($details)) {
            $this->securityRequestManager->createDetails($details);
        }

        $nonce = $this->attestationRequestManager->getChallenge(null);

        return $this->success($nonce);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws GuzzleException
     */
    public function verify(Request $request): JsonResponse
    {
        // Check if device exists
        $this->deviceService->getDevice($request);
        $verResp = $this->attestationRequestManager->verify($request->getContent());
        $publicKey = $verResp['publicKey'];
        $nonce = $verResp['nonce'];

        $response = $this->securityRequestManager->generate(compact('publicKey'));

        if ($this->container->getParameter('REQUEST_VERIFICATION') === 'yes'){
            $this->generateDeviceVerificationKey($verResp);
        }

        $shared_key = $response['deviceEncryptedSharedKey'];
        $expires = $response['expires'];

        return $this->success(compact('shared_key', 'expires', 'nonce'));
    }

    /**
     * @deprecated
     * @throws Exception
     * @throws ApiException
     * @throws GuzzleException
     */
    public function details(Request $request): Response
    {
        /** @var AttestationDetailsDto $dto */
        $dto = $this->process($request, AttestationDetailsDto::class);

        $details = $dto->getDetails();

        if (isset($details['device'])) {
            $details['device_type'] = $details['device'];
            unset($details['device']);
            $dto->setDetails($details);
        }

        $device = $this->deviceService->getDevice($request);

        $result = $this->attestationRequestManager->safetynetGetNonce($device['device_id']);

        return $this->success(['nonce' => $result['nonce']]);
    }

    /**
     * @throws ApiException
     * @throws GuzzleException
     */
    public function getNonce(Request $request): JsonResponse
    {
        $device = $this->deviceService->getDevice($request);

        $response = $this->attestationRequestManager->safetynetGetNonce($device['device_id']);

        return $this->success($response);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function safetynetVerify(Request $request): JsonResponse
    {
        // Check if device exists
        $this->deviceService->getDevice($request);

        // TODO check if sn attestation is for the same device
        $content = $request->getContent();

        $data = Utils::jsonDecode($content);

        $result = $this->attestationRequestManager->safetynetVerify($data);

        return $this->success(['is_valid' => $result['is_valid']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws ApiException
     * @throws Exception
     */
    public function setPinpadKey(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $data = Utils::jsonDecode($content);
        $key = $data['key'];

        $this->securityRequestManager->setPinpadKey(compact('key'));

        return $this->success([
            'success' => true,
        ]);
    }

    private function generateDeviceVerificationKey(array $verResp)
    {
        $this->securityRequestManager->createDeviceVerificationKey($verResp['devicePublicKey']);
    }
}
