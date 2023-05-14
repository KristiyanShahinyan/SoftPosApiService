<?php

namespace App\Controller\V1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PingController
 * @package App\Controller\V1
 *
 */
class PingController extends BaseController
{
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return JsonResponse
     */
    public function ping(): JsonResponse
    {
        return $this->success(['success' => true]);
    }

    /**
     * @return JsonResponse
     */
    public function sslPinning(): JsonResponse
    {
        return $this->success(['success' => true]);
    }
}
