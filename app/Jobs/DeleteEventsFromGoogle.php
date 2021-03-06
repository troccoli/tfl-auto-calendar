<?php

namespace App\Jobs;

use App\Events\EventsDeleted;
use App\Models\Job;
use App\Models\ShiftEvent;
use App\Traits\Throttable;
use Google\Service\Exception as GoogleServiceException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Spatie\GoogleCalendar\Event;

use function Psy\sh;

class DeleteEventsFromGoogle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Throttable;

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
        $this->throttle($this->shiftEvents, 50, function (Collection $shiftEvents) {
            /** @var ShiftEvent $shiftEvent */
            foreach ($shiftEvents as $shiftEvent) {
                try {
                    Event::find($shiftEvent->getGoogleEventId())->delete();
                } catch (GoogleServiceException $exception) {
                    if ($exception->getCode() !== 410) {
                        throw $exception;
                    }
                }
                $shiftEvent->delete();
                $this->eventsJob->incrementNumberOfEventsDeletedFromGoogle();
            }
        });
    }
}
