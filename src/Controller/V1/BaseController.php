<?php

namespace App\Controller\V1;

use Phos\Exception\ApiException;
use Phos\Helper\SerializationTrait;
use Phos\Helper\ValidationTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

abstract class BaseController extends AbstractController
{
    use SerializationTrait, ValidationTrait;

    /**
     * @param null $data
     * @param array $headers
     * @param bool $json
     * @return JsonResponse
     */
    protected function success($data = null, array $headers = [], bool $json = true): JsonResponse
    {

        $data = $this->serializer->serialize($data, 'json');

        return new JsonResponse($data, 200, $headers, $json);
    }

    /**
     * @param Request $request
     * @param string|null $dtoClass
     * @param null $groups
     * @return mixed
     * @throws ApiException
     */
    protected function process(Request $request, ?string $dtoClass, $groups = null)
    {

        if ($dtoClass === null) {
            return json_decode($request->getContent() ?: '{}', true);
        }

        $dto = $this->deserialize($request->getContent(), $dtoClass, [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

        $this->validate($dto, $groups);

        return $dto;
    }

}
