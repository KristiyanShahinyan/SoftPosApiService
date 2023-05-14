<?php

namespace App\Controller\V2;

use Phos\Controller\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PingController
 * @package App\Controller\V2
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class PingController extends AbstractApiController
{
    /**
     * @return JsonResponse
     */
    public function ping(): JsonResponse
    {
        return $this->success();
    }
}
