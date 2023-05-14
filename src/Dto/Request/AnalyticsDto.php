<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AnalyticsDto implements DtoInterface
{
    /**
     * @var string|null
     * @Assert\NotBlank(groups={"analytics"})
     * @Assert\Type("string")
     */
    protected $type;

    /**
     * @var DateTimeInterface|null
     * @Assert\NotBlank(groups={"analytics"})
     */
    protected $date;

    /**
     * @var string|null
     * @Assert\NotBlank(groups={"analytics", "sales"})
     * @Assert\Timezone
     */
    protected $timeZone;

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
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface|null $date
     */
    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string|null
     */
    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    /**
     * @param string|null $timeZone
     */
    public function setTimeZone(?string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

}
