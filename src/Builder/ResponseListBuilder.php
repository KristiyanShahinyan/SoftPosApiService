<?php

namespace App\Builder;

use App\Dto\DtoInterface;
use App\Dto\Response\ListDto;

/**
 * Class ResponseListBuilder
 * @package App\Builder
 */
abstract class ResponseListBuilder
{
    protected const DTO_CLASS_NAME = '';

    /**
     * @param array $listResponse
     * @return ListDto
     */
    public function buildListDto(array $listResponse): ListDto
    {
        /**
         * @var ListDto $listDto
         */
        $className = static::DTO_CLASS_NAME;
        $listDto = new $className();

        $listDto->setTotalItems($listResponse['total_items']);

        $itemDtos = [];

        foreach ($listResponse['items'] as $item) {
            $itemDtos[] = $this->buildDto($item);
        }

        $listDto->setItems($itemDtos);

        return $listDto;
    }

    /**
     * @param array $item
     * @return DtoInterface
     */
    abstract public function buildDto(array $item): DtoInterface;
}
