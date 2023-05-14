<?php

namespace App\Dto\Response;

use App\Dto\DtoInterface;

/**
 * Class ListDto
 * @package App\Dto\Response
 */
abstract class ListDto implements DtoInterface
{
    /**
     * @var int
     */
    protected $totalItems;

    /**
     * @return array
     */
    abstract public function getItems(): array;

    /**
     * @param array $items
     */
    abstract public function setItems(array $items): void;

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     */
    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

}
