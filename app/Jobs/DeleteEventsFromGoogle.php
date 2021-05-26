<?php

namespace App\Jobs;

use App\Events\EventsDeleted;
use App\Models\Job;
use App\Models\ShiftEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Spatie\GoogleCalendar\Event;

class DeleteEventsFromGoogle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $shiftEvents;

    public function __construct(public Job $eventsJob)
    {
    }

    public function handle(): void
    {
        $this->updateTotalNumberOfEventsToDelete();

        $this->eventsJob->startedDeleting();
        $this->deleteEvents();
        $this->eventsJob->eventsDeleted();

        EventsDeleted::dispatch($this->eventsJob);
    }

    private function updateTotalNumberOfEventsToDelete(): void
    {
        $from = $this->eventsJob->getStart()->clone()->setTime(0, 0, 0);
        $to = $this->eventsJob->getEnd()->clone()->setTime(23, 59, 59);
        $this->shiftEvents = ShiftEvent::query()
            ->between($from, $to)
            ->notForJob($this->eventsJob->getId())
            ->get();

        $this->eventsJob->setNumberOfEventsToDeleteFromGoogle($this->shiftEvents->count());
    }

    private function deleteEvents(): void
    {
        /** @var ShiftEvent $shiftEvent */
        foreach ($this->shiftEvents as $shiftEvent) {
            Event::find($shiftEvent->getGoogleEventId())->delete();
            $shiftEvent->delete();
            $this->eventsJob->incrementNumberOfEventsDeletedFromGoogle();
        }
    }
}
