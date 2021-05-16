<?php

namespace App\Jobs;

use App\Events\EventsGenerated;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Job $eventsJob)
    {
    }

    public function handle(): void
    {
        $this->updateTotalNumberOfShifts();

        $this->eventsJob->generating();
        $this->generateEvents();
        $this->eventsJob->eventsGenerated();

        EventsGenerated::dispatch($this->eventsJob);
    }

    private function updateTotalNumberOfShifts(): void
    {
        $end = $this->eventsJob->getEnd()->clone()->addWeek();
        $shifts = $end->diffInDays($this->eventsJob->getStart());

        $this->eventsJob->setNumberOfShiftsToGenerate($shifts);
    }

    private function generateEvents(): void
    {
        Log::info("Generating {$this->eventsJob->total_shifts} events");

        $start = $this->eventsJob->getStart()->clone();
        for ($day = 1; $day <= $this->eventsJob->total_shifts; $day++) {
            sleep(1);
            $start->addDay();
            $this->eventsJob->incrementNumberOfShifts();
        }
    }
}
