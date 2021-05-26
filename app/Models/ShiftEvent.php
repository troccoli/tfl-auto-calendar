<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftEvent extends Model
{
    use SoftDeletes;

    protected $fillable = ['job_id', 'title', 'start', 'end', 'is_all_day'];
    protected $dates = ['start', 'end'];
    protected $casts = [
        'is_all_day' => 'boolean',
    ];

    static public function createShiftEvent(int $jobId, string $title, Carbon $start, Carbon $end): self
    {
        return self::create([
            'job_id' => $jobId,
            'title' => $title,
            'start' => $start,
            'end' => $end,
        ]);
    }

    static public function createAllDayShiftEvent(int $jobId, string $title, Carbon $start, Carbon $end): self
    {
        return self::create([
            'job_id' => $jobId,
            'title' => $title,
            'start' => $start->clone()->setTime(0, 0, 0),
            'end' => $end->clone()->setTime(23, 59, 59),
            'is_all_day' => true,
        ]);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function getGoogleEventId(): string
    {
        return $this->google_id;
    }

    public function scopeBetween(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('start', [$start, $end]);
    }

    public function scopeNotForJob(Builder $query, int $jobId): Builder
    {
        return $query->where('job_id', '<>', $jobId);
    }
}
