<?php

namespace App\Controller\V1;

use App\RequestManager\Security\SecurityRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SharedKeyController
 * @package App\Controller\V1
 */
class SharedKeyController extends BaseController
{
    private SecurityRequestManager $securityRequestManager;

    public function __construct(SecurityRequestManager $securityRequestManager)
    {
        $this->securityRequestManager = $securityRequestManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws ApiException
     */
    public function renewKey(Request $request): JsonResponse
    {
        $key = $this->securityRequestManager->renew($request);

        return $this->success($key);
    }

    /**
     * @return JsonResponse
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sessionInit(): JsonResponse
    {
        $data = $this->securityRequestManager->sessionInit();
        return $this->success(compact('data'));
    }

    public function sessionPing(){
        $pong = $this->securityRequestManager->sessionInit();
        return $this->success($pong);
    }
}
