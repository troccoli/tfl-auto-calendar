<?php

namespace App\Http\Livewire;

use App\Events\JobCreated;
use App\Models\Holiday;
use App\Models\Job;
use App\Services\Rota;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class GenerateEvents extends Component
{
    public $startDate;
    public $endDate;
    public $position;
    public $holidayPeriods;

    public function mount()
    {
        $this->holidayPeriods = 0;
    }

    public function render()
    {
        return view('livewire.generate-events');
    }

    public function getRules(): array
    {
        $lastPosition = Rota::factory()->getNumberOfPositions();
        return [
            'startDate' => 'required|date|exclude_if:endDate,null|before:endDate',
            'position' => 'required|int|min:1|max:'.$lastPosition,
            'endDate' => 'required|date|after:startDate',
        ];
    }

    public function getMessages(): array
    {
        $lastPosition = Rota::factory()->getNumberOfPositions();
        return [
            'startDate.required' => 'Please enter a start date.',
            'startDate.date' => 'Please enter a valid date',
            'startDate.before' => 'The start date must be before the end date',
            'position.int' => 'The position must be a positive number.',
            'position.min' => 'The position must be a positive number.',
            'position.max' => 'The last possible position is '.$lastPosition,
            'endDate.required' => 'Please enter an end date.',
            'endDate.date' => 'Please enter a valid date',
            'endDate.after' => 'The end date must be after the start date',
        ];
    }

    public function updated($propertyName)
    {
        try {
            $this->validateOnly($propertyName);
            $this->holidayPeriods = $this->checkHolidays();
        } catch (ValidationException $e) {
            $this->holidayPeriods = 0;
            throw $e;
        }
    }

    public function generateEvents(Rota $service)
    {
        $this->validate();

        $job = Job::createJob(
            Carbon::createFromFormat('D M d Y', $this->startDate),
            Carbon::createFromFormat('D M d Y', $this->endDate),
            $this->position
        );

        JobCreated::dispatch($job);

        return redirect()->route('dashboard');
    }

    private function checkHolidays(): int
    {
        if (null === $this->startDate) {
            return 0;
        }
        $start = Carbon::createFromFormat('D M d Y', $this->startDate);

        if (null === $this->endDate) {
            return 0;
        }
        $end = Carbon::createFromFormat('D M d Y', $this->endDate);

        if ($start->greaterThanOrEqualTo($end)) {
            return 0;
        }

        return Holiday::query()->between($start, $end)->count();
    }
}
