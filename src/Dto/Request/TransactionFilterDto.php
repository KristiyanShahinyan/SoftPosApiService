<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TransactionFilterDto.
 */
class TransactionFilterDto implements DtoInterface
{
    /**
     * @var DateTime|null
     * @Assert\Type("DateTime")
     */
    protected $startDate;

    /**
     * @var DateTime|null
     * @Assert\Type("DateTime")
     */
    protected $endDate;

    /**
     * @var int|null
     * @Assert\Type("int")
     */
    protected $trnType;

    /**
     * @var int|null
     * @Assert\Type("int")
     *
     * 1 deposit
     * 2 withdraw
     * 3 send
     * 4 request
     * 5 payment
     * 6 gps
     * 7 bank deposit
     * 8 receive
     * 9 transfer
     * 11 atm
     * 10 pos
     * 12 auth
     * 13 order card
     * 14 revert
     * 15 fee
     * 16 refund
     * 17 void
     *
     */
    protected $status;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $user;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $merchant;

    /**
     * @var int|null
     * @Assert\Type("int")
     */
    protected $page;

    /**
     * @var int|null
     * @Assert\Type("int")
     */
    protected $limit;

    /**
     * 3D
     *
     * @var bool|null
     * @Assert\Type("bool")
     */
    protected $threeDs;

    /**
     * @var string|null
     */
    protected $tid;

    /**
     * @var string|null
     */
    protected $mid;

    /**
     * @var string|null
     */
    protected $acquirerCode;

    protected ?array $sort = null;

    protected ?array $trnTypes = null;

    private ?bool $exactDate = null;

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
     * @return int|null
     */
    public function getTrnType(): ?int
    {
        return $this->trnType;
    }

    /**
     * @param int|null $trnType
     */
    public function setTrnType(?int $trnType): void
    {
        $this->trnType = $trnType;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getMerchant(): ?string
    {
        return $this->merchant;
    }

    /**
     * @param string|null $merchant
     */
    public function setMerchant(?string $merchant): void
    {
        $this->merchant = $merchant;
    }

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
     * @return string|null
     */
    public function getTid(): ?string
    {
        return $this->tid;
    }

    /**
     * @param string|null $tid
     */
    public function setTid(?string $tid): void
    {
        $this->tid = $tid;
    }

    /**
     * @return string|null
     */
    public function getMid(): ?string
    {
        return $this->mid;
    }

    /**
     * @param string|null $mid
     */
    public function setMid(?string $mid): void
    {
        $this->mid = $mid;
    }

    /**
     * @return string|null
     */
    public function getAcquirerCode(): ?string
    {
        return $this->acquirerCode;
    }

    /**
     * @param string|null $acquirerCode
     */
    public function setAcquirerCode(?string $acquirerCode): void
    {
        $this->acquirerCode = $acquirerCode;
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
