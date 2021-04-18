<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class Week
{
    private int $number;
    private bool $leaveCover;
    private Collection $shifts;

    public function __construct(int $number, bool $leaveCover, ?Collection $shifts = null)
    {
        $this->number = $number;
        $this->leaveCover = $leaveCover;
        $this->shifts = $shifts ?? new Collection();
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function isLeaveCover(): bool
    {
        return $this->leaveCover;
    }

    public function getShifts(): Collection
    {
        return $this->shifts;
    }
}
