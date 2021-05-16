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
        // does it job
        $delay = mt_rand(2,10);
        Log::info("Sending events for job {$this->eventsJob->id}. It will take $delay seconds.");
        sleep($delay);
        Log::info("Events sent");
        EventsSent::dispatch($this->eventsJob);
    }
}
