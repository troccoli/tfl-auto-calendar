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

class GenerateEvents implements ShouldQueue
{
    public const EVENT_PREFIX = 'M Working';

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Job $eventsJob)
    {
    }

    public function handle(Rota $service): void
    {
        $this->updateTotalNumberOfShifts();

        $this->eventsJob->startedGenerating();
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
        $currentDate = $this->eventsJob->getStart()->clone();
        $position = $this->eventsJob->getPosition();
        $lastPosition = $service->getNumberOfPositions();
        while ($currentDate->isBefore($this->eventsJob->getEnd())) {
            $week = $service->getWeek($position);
            if ($week->isLeaveCover()) {
                ShiftEvent::createAllDayShiftEvent(
                    $this->eventsJob->getId(),
                    'M (Leave Cover)',
                    $currentDate,
                    $currentDate->clone()->addWeek()->subDay()
                );
                $currentDate->addWeek();
                $this->eventsJob->incrementNumberOfShiftsGenerated(7);
            } else {
                /** @var Duty $duty */
                foreach ($week->getDuties() as $duty) {
                    if ($duty->isRestDay()) {
                        $currentDate->addDay();
                        $this->eventsJob->incrementNumberOfShiftsGenerated();
                        continue;
                    }

                    [$startDateTime, $endDateTime] = $this->calculateDateTimes($currentDate, $duty);
                    ShiftEvent::createShiftEvent(
                        $this->eventsJob->getId(),
                        'M Working'.($duty->isSpare() ? " (Spare)" : ""),
                        $startDateTime,
                        $endDateTime
                    );
                    $currentDate->addDay();
                    $this->eventsJob->incrementNumberOfShiftsGenerated();
                }
            }
            $position = $position === $lastPosition ? 1 : $position + 1;
            $this->eventsJob->incrementNumberOfShiftsGenerated();
        }
    }

    private function calculateDateTimes(Carbon $start, Duty $duty): array
    {
        preg_match("/(\d\d):(\d\d)/", $duty->getStart(), $matches);
        $startDateTime = $start->clone()
            ->setTimezone('Europe/London')->setTime($matches[1], $matches[2])
            ->setTimezone('UTC');

        preg_match("/(\d\d):(\d\d)/", $duty->getEnd(), $matches);
        $endDateTime = $start->clone()
            ->setTimezone('Europe/London')->setTime($matches[1], $matches[2])
            ->setTimezone('UTC');

        if ($endDateTime->isBefore($startDateTime)) {
            $endDateTime->addDay();
        }

        return [$startDateTime, $endDateTime];
    }
}
