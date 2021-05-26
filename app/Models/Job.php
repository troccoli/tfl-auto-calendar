<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    private const CREATED = 1;
    private const GENERATING_EVENTS = 2;
    private const EVENTS_GENERATED = 3;
    private const DELETING_EVENTS = 4;
    private const EVENTS_DELETED = 5;
    private const SENDING_EVENTS = 6;
    private const EVENTS_SENT = 7;
    private const FINISHED = 8;
    private const FAILED = 9;

    protected $fillable = ['start', 'end', 'position', 'status'];
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

    public function shiftEvents(): HasMany
    {
        return $this->hasMany(ShiftEvent::class);
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

    public function setNumberOfShiftsToGenerate(int $value): void
    {
        $this->total_shifts = $value;
        $this->save();
    }

    public function incrementNumberOfShiftsGenerated(int $value = 1): void
    {
        $this->generated_shifts += $value;
        $this->save();
    }

    public function generatingProgress(): int
    {
        return round($this->generated_shifts / $this->total_shifts * 100);
    }

    public function setNumberOfEventsToDeleteFromGoogle(int $value): void
    {
        $this->total_events_to_delete = $value;
        $this->save();
    }

    public function incrementNumberOfEventsDeletedFromGoogle(int $value = 1): void
    {
        $this->events_deleted += $value;
        $this->save();
    }

    public function deletingProgress(): int
    {
        return round($this->events_deleted / $this->total_events_to_delete * 100);
    }

    public function setNumberOfEventsToSendToGoogle(int $value): void
    {
        $this->total_events = $value;
        $this->save();
    }

    public function incrementNumberOfEventsSentToGoogle(int $value = 1): void
    {
        $this->events_sent += $value;
        $this->save();
    }

    public function sendingProgress(): int
    {
        return round($this->events_sent / $this->total_events * 100);
    }

    public function wasCreated(): bool
    {
        return $this->status === self::CREATED;
    }

    public function isProcessing(): bool
    {
        return in_array($this->status, [
            self::GENERATING_EVENTS,
            self::EVENTS_GENERATED,
            self::DELETING_EVENTS,
            self::EVENTS_DELETED,
            self::SENDING_EVENTS,
            self::EVENTS_SENT,
        ]);
    }

    public function isWaiting(): bool
    {
        return in_array($this->status, [self::EVENTS_GENERATED, self::EVENTS_DELETED, self::EVENTS_SENT]);
    }

    public function isGenerating(): bool
    {
        return $this->status === self::GENERATING_EVENTS;
    }

    public function isDeleting(): bool
    {
        return $this->status === self::DELETING_EVENTS;
    }

    public function isSending(): bool
    {
        return $this->status === self::SENDING_EVENTS;
    }

    public function hasFinished(): bool
    {
        return $this->status === self::FINISHED;
    }

    public function hasFailed(): bool
    {
        return $this->status === self::FAILED;
    }

    public function startedGenerating(): void
    {
        $this->changeStatus(self::GENERATING_EVENTS);
    }

    public function eventsGenerated(): void
    {
        $this->changeStatus(self::EVENTS_GENERATED);
    }

    public function startedDeleting(): void
    {
        $this->changeStatus(self::DELETING_EVENTS);
    }

    public function eventsDeleted(): void
    {
        $this->changeStatus(self::EVENTS_DELETED);
    }

    public function startedSending(): void
    {
        $this->changeStatus(self::SENDING_EVENTS);
    }

    public function eventsSent(): void
    {
        $this->changeStatus(self::EVENTS_SENT);
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
    }
}
