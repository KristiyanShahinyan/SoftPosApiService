<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use DateTime;

class TransactionsDto implements DtoInterface
{
    /**
     * @var int|null
     */
    protected $page;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var bool|null
     */
    protected $threeDs;

    /**
     * @var DateTime|null
     */
    protected $date;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var int|null
     */
    protected $status;

    protected ?array $sort = null;

    protected ?array $trnTypes = null;

    private ?DateTime $startDate = null;

    private ?DateTime $endDate = null;

    private ?bool $exactDate = null;

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return bool|null
     */
    public function getThreeDs(): ?bool
    {
        return $this->threeDs;
    }

    /**
     * @param bool|null $threeDs
     */
    public function setThreeDs(bool $threeDs): void
    {
        $this->threeDs = $threeDs;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime|null $date
     */
    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @param array|null $sort
     */
    public function setSort(?array $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return array|null
     */
    public function getTrnTypes(): ?array
    {
        return $this->trnTypes;
    }

    /**
     * @param array|null $trnTypes
     */
    public function setTrnTypes(?array $trnTypes): void
    {
        $this->trnTypes = $trnTypes;
    }

    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     */
    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     */
    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return bool|null
     */
    public function getExactDate(): ?bool
    {
        return $this->exactDate;
    }

    /**
     * @param bool|null $exactDate
     */
    public function setExactDate(?bool $exactDate): void
    {
        $this->exactDate = $exactDate;
    }
}
