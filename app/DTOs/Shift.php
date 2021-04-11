<?php

namespace App\DTOs;

class Shift
{
    private int $day;
    private bool $restDay;
    private string $start;
    private string $end;

    public function __construct(int $day, bool $restDay, ?string $start = null, ?string $end = null)
    {
        $this->day = $day;
        $this->restDay = $restDay;
        $this->start = $start ?? '';
        $this->end = $end ?? '';
    }

    public function getDay(): int
    {
        return $this->day;
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
}
