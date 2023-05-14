<?php

namespace App\Dto\Response;

class TransactionListDto extends ListDto
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

}
