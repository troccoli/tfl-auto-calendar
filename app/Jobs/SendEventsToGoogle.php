<?php

namespace App\Jobs;

use App\Events\EventsSent;
use App\Models\Job;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEventsToGoogle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(public Job $eventsJob)
    {
    }

    public function handle(): void
    {
        $this->updateTotalNumberOfEvents();

        $this->eventsJob->startedSending();
        $this->sendEvents();
        $this->eventsJob->eventsSent();

        EventsSent::dispatch($this->eventsJob);
    }

    private function updateTotalNumberOfEvents(): void
    {
        $end = $this->eventsJob->getEnd()->clone()->addWeek();
        $shifts = $end->diffInDays($this->eventsJob->getStart());

        $this->eventsJob->setNumberOfEventsToSendToGoogle($shifts);
    }

    private function sendEvents(): void
    {
        Log::info("Sending {$this->eventsJob->total_events} events");

        $start = $this->eventsJob->getStart()->clone();
        for ($day = 1; $day <= $this->eventsJob->total_events; $day++) {
            sleep(1);
            $start->addDay();
            $this->eventsJob->incrementNumberOfEventsSentToGoogle();
        }
    }
}
