<?php

namespace App\Listeners;

use App\Events\EventsGenerated;
use App\Events\EventsSent;
use App\Events\JobCreated;
use App\Events\JobFinished;
use App\Jobs\GenerateEvents;
use App\Jobs\SendEventsToGoogle;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

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
            [JobEventsSubscriber::class, 'sendEvents']
        );

        $dispatcher->listen(
            EventsSent::class,
            [JobEventsSubscriber::class, 'finishJob']
        );
    }

    public function generateEvents(JobCreated $event): void
    {
        $event->job->started();
        GenerateEvents::dispatch($event->job);
    }

    public function sendEvents(EventsGenerated $event): void
    {
        SendEventsToGoogle::dispatch($event->job);
    }

    public function finishJob($event): void
    {
        $event->job->completed();
        JobFinished::dispatch($event->job);
    }
}
