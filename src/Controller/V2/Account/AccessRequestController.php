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
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccessRequestController
 * @package App\Controller\V2\Account
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class AccessRequestController extends AbstractApiController
{
    /**
     * @var AccessRequestManager
     */
    private AccessRequestManager $manager;

    /**
     * AccessRequestController constructor.
     * @param AccessRequestManager $manager
     */
    public function __construct(AccessRequestManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws GuzzleException
     * @throws ApiException
     */
    public function create(Request $request)
    {
        /**
         * @var $dto AccessRequestDto
         */
        $dto = $this->deserialize($request->getContent(), AccessRequestDto::class);

        $this->validate($dto);

        $dto->setUser($this->getUser()->getId());

        $this->manager->create($dto);

        return $this->success();
    }
}
