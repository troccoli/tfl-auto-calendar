<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

class Duty
{
    private int $day;
    private ?int $number;
    private bool $restDay;
    private string $start;
    private string $end;

    public function __construct(int $day, ?int $number, bool $restDay, ?string $start = null, ?string $end = null)
    {
        $this->day = $day;
        $this->number = $number;
        $this->restDay = $restDay;
        $this->start = $start ?? '';
        $this->end = $end ?? '';
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function isRestDay(): bool
    {
        return $this->restDay;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }

    public function isSpare(): bool
    {
        if (null === $this->number) {
            return false;
        }

        switch ($this->day) {
            case Carbon::SUNDAY:
                return $this->number >= 31 && $this->number <= 37;
            case Carbon::SATURDAY:
                return $this->number >= 41 && $this->number <=49;
            default:
                return $this->number >= 49 && $this->number <= 60;
        }
    }
}
