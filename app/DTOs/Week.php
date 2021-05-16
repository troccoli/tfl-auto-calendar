<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class Week
{
    protected function __construct(private int $number, private bool $leaveCover, private ?Collection $duties = null)
    {
        $this->duties = $duties ?? new Collection();
    }

    static public function createLeaveCoverWeek(int $number): self
    {
        return new Week($number, true, null);
    }

    static public function createWeekWithDuties(int $number, Collection $duties): Week
    {
        return new Week($number, false, $duties);
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function isLeaveCover(): bool
    {
        return $this->leaveCover;
    }

    public function getDuties(): Collection
    {
        return $this->duties;
    }
}
