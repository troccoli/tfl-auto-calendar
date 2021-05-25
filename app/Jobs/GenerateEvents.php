<?php

namespace App\Jobs;

use App\DTOs\Duty;
use App\Events\EventsGenerated;
use App\Models\Job;
use App\Models\ShiftEvent;
use App\Services\Rota;
use Carbon\Carbon;
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

    public function handle(Rota $service): void
    {
        $this->updateTotalNumberOfShifts();

        $this->eventsJob->generating();
        $this->generateEvents($service);
        $this->eventsJob->eventsGenerated();

        EventsGenerated::dispatch($this->eventsJob);
    }

    private function updateTotalNumberOfShifts(): void
    {
        $shifts = $this->eventsJob->getEnd()->diffInDays($this->eventsJob->getStart()) + 1;
        $this->eventsJob->setNumberOfShiftsToGenerate($shifts);
    }

    private function generateEvents(Rota $service): void
    {
        Log::info("Generating {$this->eventsJob->total_shifts} events");

        $start = $this->eventsJob->getStart()->clone();
        $position = $this->eventsJob->getPosition();
        $lastPosition = $service->getNumberOfPositions();
        while ($start->isBefore($this->eventsJob->getEnd())) {
            $week = $service->getWeek($position);
            if ($week->isLeaveCover()) {
                ShiftEvent::createAllDayShiftEvent(
                    $this->eventsJob->getId(),
                    'M (Leave Cover)',
                    $start,
                    $start->clone()->addWeek()->subDay()
                );
                $start->addWeek();
                $this->eventsJob->incrementNumberOfShifts(7);
            } else {
                /** @var Duty $duty */
                foreach ($week->getDuties() as $duty) {
                    if ($duty->isRestDay()) {
                        $start->addDay();
                        $this->eventsJob->incrementNumberOfShifts();
                        continue;
                    }

                    [$startDateTime, $endDateTime] = $this->calculateDateTimes($start, $duty);
                    ShiftEvent::createShiftEvent(
                        $this->eventsJob->getId(),
                        'M '.($duty->isSpare() ? "(Spare)" : "Working"),
                        $startDateTime,
                        $endDateTime
                    );
                    $start->addDay();
                    $this->eventsJob->incrementNumberOfShifts();
                }
            }
            $position = $position === $lastPosition ? 1 : $position + 1;
            $this->eventsJob->incrementNumberOfShifts();
        }
    }

    private function calculateDateTimes(Carbon $start, Duty $duty): array
    {
        preg_match("/(\d\d):(\d\d)/", $duty->getStart(), $matches);
        $startDateTime = $start->clone()->setTime($matches[1], $matches[2], 0);

        preg_match("/(\d\d):(\d\d)/", $duty->getEnd(), $matches);
        $endDateTime = $start->clone()->setTime($matches[1], $matches[2], 0);

        if ($endDateTime->isBefore($startDateTime)) {
            $endDateTime->addDay();
        }

        return [$startDateTime, $endDateTime];
    }
}
