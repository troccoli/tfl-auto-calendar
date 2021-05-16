<?php

namespace App\Listeners;

use App\Events\JobCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateBatch implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  JobCreated  $event
     * @return void
     */
    public function handle(JobCreated $event)
    {
        //
    }
}
