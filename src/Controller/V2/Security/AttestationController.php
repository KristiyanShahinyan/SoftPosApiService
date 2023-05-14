<?php

namespace App\Controller\V2\Security;

use App\Dto\Request\AttestationDetailsDto;
use App\Dto\Request\AttestationDto;
use App\Dto\Request\AttestationVerifyDto;
use App\Helper\Utils;
use App\RequestManager\Attestation\AttestationRequestManager;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Controller\AbstractApiController;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Controller\V2
 */
class AttestationController extends AbstractApiController
{
    /**
     * @var AttestationRequestManager
     */
    protected AttestationRequestManager $manager;

    /**
     * AttestationController constructor.
     * @param AttestationRequestManager $manager
     */
    public function __construct(AttestationRequestManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function init(Request $request): JsonResponse
    {
        $dto = new AttestationDto();
        $dto->setDeviceId(Utils::parseDeviceId($request));
        $dto->setAppType(Utils::parseAppType($request));

        $result = $this->manager->getChallenge($dto);

        return $this->success($result);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function verify(Request $request): JsonResponse
    {
        $content = str_replace("\n", '', $request->getContent());

        /**
         * @var AttestationVerifyDto $dto
         */
        $dto = $this->serializer->deserialize($content, AttestationVerifyDto::class, 'json');

        $this->validate($dto);

        $dto->setDeviceId(Utils::parseDeviceId($request));
        $dto->setAppType(Utils::parseAppType($request));

        $result = $this->manager->getSharedKey($dto);

        return $this->success($result);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     * @throws GuzzleException
     */
    public function details(Request $request): Response
    {
        /**
         * @var AttestationDetailsDto $dto
         */
        $dto = $this->deserialize($request->getContent(), AttestationDetailsDto::class);

        $this->validate($dto);

        $dto->setDeviceId(Utils::parseDeviceId($request));
        $dto->setAppType(Utils::parseAppType($request));

        $this->manager->setDetails($dto);

        // TODO send device ID on sn attestation to be saved to DB
        $result = $this->manager->safetynetGetNonce(Utils::parseDeviceId($request));

        return $this->success([
            'nonce' => Utils::base64Encode($result['nonce']),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws Exception
     */
    public function safetynetVerify(Request $request): JsonResponse
    {
        // TODO check if sn attestation is for the same device
        $content = $request->getContent();

        $data = Utils::jsonDecode($content);

        $result = $this->manager->safetynetVerify($data);

        return $this->success([
            'is_valid' => $result['is_valid'],
        ]);
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

        $result = $this->manager->setPinpadKey([
            'device_id' => Utils::parseDeviceId($request),
            'key'       => $data['key'],
        ]);

        return $this->success([
            'success' => true,
        ]);
    }
}
