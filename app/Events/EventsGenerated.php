<?php

namespace App\Events;

use App\Models\Job;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventsGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Job $job)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
