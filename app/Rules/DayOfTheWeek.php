<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DayOfTheWeek implements Rule
{
    public function __construct(private int $dayOfTheWeek)
    {
        $this->weekDay = Carbon::now()->setDay($this->dayOfTheWeek)->englishDayOfWeek;
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return Carbon::parse($value)->dayOfWeek === $this->dayOfTheWeek;
    }

    public function message(): string
    {
        return "The :attribute must be a ".Carbon::getDays()[$this->dayOfTheWeek];
    }
}
