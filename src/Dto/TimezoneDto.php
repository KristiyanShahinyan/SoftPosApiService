<?php

namespace App\Dto;

class TimezoneDto
{
    protected string $name;

    protected string $timeDifference;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTimeDifference(): string
    {
        return $this->timeDifference;
    }

    public function setTimeDifference(string $timeDifference): void
    {
        $this->timeDifference = $timeDifference;
    }
}