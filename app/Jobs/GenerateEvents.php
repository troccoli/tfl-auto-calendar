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
        // does its job
        $delay = mt_rand(2,10);
        Log::info("Generating events for job {$this->eventsJob->id}. It will take $delay seconds.");
        sleep($delay);
        Log::info("Events generated");
        EventsGenerated::dispatch($this->eventsJob);
    }
}
