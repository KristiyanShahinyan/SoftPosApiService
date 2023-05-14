<?php

namespace App\Controller\V2\Account;

use App\Dto\Request\AccessRequestDto;
use App\RequestManager\Account\AccessRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Controller\AbstractApiController;
use Phos\Exception\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MerchantController
 * @package App\Controller\V2
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class MerchantController extends AbstractApiController
{
    /**
     * @var AccessRequestManager
     */
    private AccessRequestManager $accessRequestManager;

    /**
     * MerchantController constructor.
     * @param AccessRequestManager $accessRequestManager
     */
    public function __construct(AccessRequestManager $accessRequestManager)
    {
        $this->accessRequestManager = $accessRequestManager;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function accessRequest(Request $request): JsonResponse
    {
        /**
         * @var $dto AccessRequestDto
         */
        $dto = $this->deserialize($request->getContent(), AccessRequestDto::class);

        $this->validate($dto);

        $dto->setUser($this->getUser()->getId());

        $this->accessRequestManager->create($dto);

        return $this->success();
    }
}
