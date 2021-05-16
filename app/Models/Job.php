<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    private const CREATED = 0;
    private const PROCESSING = 1;
    private const FINISHED = 2;
    private const FAILED = 3;

    protected $fillable = ['start', 'end', 'position', 'status',];
    protected $dates = ['start', 'end'];

    public function getId(): int
    {
        return $this->id;
    }

    public static function createJob(Carbon $start, Carbon $end, int $position): self
    {
        return Job::create([
            'start' => $start,
            'end' => $end,
            'position' => $position,
            'status' => self::CREATED,
        ]);
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function getEnd(): Carbon
    {
        return $this->end;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function wasCreated(): bool
    {
        return $this->status === self::CREATED;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::PROCESSING;
    }

    public function hasFinished(): bool
    {
        return $this->status === self::FINISHED;
    }

    public function hasFailed(): bool
    {
        return $this->status === self::FAILED;
    }

    public function started(): void
    {
        $this->changeStatus(self::PROCESSING);
    }

    public function failed(): void
    {
        $this->changeStatus(self::FAILED);
    }

    public function completed(): void
    {
        $this->changeStatus(self::FINISHED);
    }

    private function changeStatus(int $status): void
    {
        $this->status = $status;
        $this->save();
        $this->refresh();
    }
}
