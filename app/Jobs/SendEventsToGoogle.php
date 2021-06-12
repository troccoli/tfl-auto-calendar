<?php

namespace App\Jobs;

use App\Events\EventsSent;
use App\Models\Job;
use App\Models\ShiftEvent;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Spatie\GoogleCalendar\Event;

class SendEventsToGoogle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private Collection $shiftEvents;

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
        $this->shiftEvents = $this->eventsJob->shiftEvents;
        $this->eventsJob->setNumberOfEventsToSendToGoogle($this->shiftEvents->count());
    }

    private function sendEvents(): void
    {
        /** @var ShiftEvent $shiftEvent */
        foreach ($this->shiftEvents as $shiftEvent) {
            $googleEvent = $this->sendToGoogle($shiftEvent);
            $shiftEvent->setGoogleEventId($googleEvent->id);
            $shiftEvent->save();
            $this->eventsJob->incrementNumberOfEventsSentToGoogle();
        }
    }

    private function sendToGoogle(ShiftEvent $shiftEvent): Event
    {
        $googleEvent = new Event();

        $googleEvent->name = $shiftEvent->getTitle();
        $googleEvent->description = 'Automatically created for you by '.config('app.name');

        if ($shiftEvent->isAllDayEvent()) {
            $googleEvent->startDate = $shiftEvent->getStart()->setTimezone('Europe/London');
            $googleEvent->endDate = $shiftEvent->getEnd()->setTimezone('Europe/London');
        } else {
            $googleEvent->startDateTime = $shiftEvent->getStart()->setTimezone('Europe/London');
            $googleEvent->endDateTime = $shiftEvent->getEnd()->setTimezone('Europe/London');
        }

        return $googleEvent->save();
    }
}
