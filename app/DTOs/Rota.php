<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Rota
{
    public function __construct(private Carbon $startDate, private Collection $weeks)
    {
        // abort if any of the item in $weeks is NOT a Week DTO
    }

    public function getStart(): Carbon
    {
        return $this->startDate;
    }

    public function getWeek(int $position): ?Week
    {
        return $this->weeks->get($position);
    }

    public function getWeeks(): Collection
    {
        return $this->weeks;
    }

    public function getTotalPositions(): int
    {
        return $this->weeks->count();
    }
}
