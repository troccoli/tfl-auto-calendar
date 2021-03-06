<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    public $timestamps = false;

    protected $dates = ['start'];
    protected $fillable = ['start'];

    public function getId(): int
    {
        return $this->id;
    }

    public function getStart(): Carbon
    {
        return $this->start;
    }

    public function scopeInDescendingOrder(Builder $query): Builder
    {
        return $query->orderByDesc('start');
    }

    public function scopeInAscendingOrder(Builder $query): Builder
    {
        return $query->orderBy('start');
    }

    public function scopeBetween(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->whereBetween('start', [$start, $end]);
    }

    public function scopeComingUp(Builder $query): Builder
    {
        return $query->where('start', '>=', Carbon::now()->subWeeks(2));
    }
}
