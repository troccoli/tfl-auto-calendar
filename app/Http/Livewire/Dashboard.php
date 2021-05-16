<?php

namespace App\Http\Livewire;

use App\Models\Job;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $jobs = Job::latest()->get();
        return view('livewire.dashboard', compact('jobs'));
    }
}
