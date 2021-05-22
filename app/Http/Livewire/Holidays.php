<?php

namespace App\Http\Livewire;

use App\Models\Holiday;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Holidays extends Component
{
    use WithPagination;

    public function render()
    {
        $holidays = Holiday::query()->inDescendingOrder()->paginate(10);

        return view('livewire.holidays', compact('holidays'));
    }

    public function addHoliday(string $date)
    {
        $start = Carbon::createFromFormat('Y-n-j', $date)->setTime(0,0,0);
        if (Holiday::query()->where('start', $start)->first() === null) {
            Holiday::create([
                'start' => $start,
            ]);
        }
    }

    public function deleteHoliday(int $id)
    {
        Holiday::find($id)->delete();
    }
}
