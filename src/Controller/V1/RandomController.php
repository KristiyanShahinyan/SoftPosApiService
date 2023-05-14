<?php

namespace App\Controller\V1;

use App\Service\RandomService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class RandomController
 * @package App\Controller\V1
 */
class RandomController extends BaseController
{
    protected RandomService $randomService;

    public function __construct(RandomService $randomService)
    {
        $this->randomService = $randomService;
    }

    /**
     * @param int $bytes
     * @return JsonResponse
     */
    public function random(int $bytes = 32): JsonResponse
    {
        return $this->success(['random' => $this->randomService->generateRandom($bytes)]);
    }
}
