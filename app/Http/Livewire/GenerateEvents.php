<?php

namespace App\Http\Livewire;

use App\Events\JobCreated;
use App\Models\Job;
use App\Services\Rota;
use Carbon\Carbon;
use Livewire\Component;

class GenerateEvents extends Component
{
    public $startDate;
    public $endDate;
    public $position;

    private $lastPosition;

    public function mount(Rota $service)
    {
        $this->lastPosition = $service->getNumberOfPositions();
    }

    public function render()
    {
        return view('livewire.generate-events');
    }

    public function getRules(): array
    {
        return [
            'startDate' => 'required|date',
            'position' => 'required|int|min:1|max:'.$this->lastPosition,
            'endDate' => 'required|date|after:startDate',
        ];
    }

    public function getMessages(): array
    {
        return [
            'startDate.required' => 'Please enter a start date.',
            'startDate.date' => 'Please enter a valid date',
            'position.int' => 'The position must be a positive number.',
            'position.min' => 'The position must be a positive number.',
            'position.max' => 'The last possible position is '.$this->lastPosition,
            'endDate.required' => 'Please enter an end date.',
            'endDate.date' => 'Please enter a valid date',
            'endDate.after' => 'The end date must be after the start date',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
}
