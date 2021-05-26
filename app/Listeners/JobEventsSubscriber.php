<?php

namespace App\Listeners;

use App\Events\EventsDeleted;
use App\Events\EventsGenerated;
use App\Events\EventsSent;
use App\Events\JobCreated;
use App\Jobs\DeleteEventsFromGoogle;
use App\Jobs\GenerateEvents;
use App\Jobs\SendEventsToGoogle;
use Illuminate\Events\Dispatcher;

class JobEventsSubscriber
{
    public function subscribe(Dispatcher $dispatcher)
    {
        $dispatcher->listen(
            JobCreated::class,
            [JobEventsSubscriber::class, 'generateEvents']
        );

        $dispatcher->listen(
            EventsGenerated::class,
            [JobEventsSubscriber::class, 'deleteEvents']
        );

        $dispatcher->listen(
            EventsDeleted::class,
            [JobEventsSubscriber::class, 'sendEvents']
        );

        $dispatcher->listen(
            EventsSent::class,
            [JobEventsSubscriber::class, 'finishJob']
        );
    }

    public function generateEvents(JobCreated $event): void
    {
        GenerateEvents::dispatch($event->job);
    }

    public function deleteEvents(EventsGenerated $event): void
    {
        DeleteEventsFromGoogle::dispatch($event->job);
    }

    public function sendEvents(EventsDeleted $event): void
    {
        SendEventsToGoogle::dispatch($event->job);
    }

    public function finishJob($event): void
    {
        $event->job->completed();
    }
}
