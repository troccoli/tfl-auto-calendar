<?php

namespace App\Services;

use App\DTOs\Duty;
use App\DTOs\Rota as RotaDTO;
use App\DTOs\Week;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\PdfToText\Exceptions\CouldNotExtractText;
use Spatie\PdfToText\Pdf;

class Rota
{
    private const DAYS = [
        2 => Carbon::SUNDAY,
        3 => Carbon::MONDAY,
        4 => Carbon::TUESDAY,
        5 => Carbon::WEDNESDAY,
        6 => Carbon::THURSDAY,
        7 => Carbon::FRIDAY,
        8 => Carbon::SATURDAY,
    ];

    private ?RotaDTO $rota = null;

    public function getWeek(int $position): Week
    {
        return $this->getRota()->getWeek($position);
    }

    public function getRotaStart(): Carbon
    {
        return $this->getRota()->getStart();
    }

    public function getRota(): RotaDTO
    {
        if (null === $this->rota) {
            $this->rota = Cache::remember('rota', CarbonInterval::day(), function (): RotaDTO {
                $pdf = (new Pdf())->setPdf(resource_path('rota.pdf'));
                $weeks = new Collection();
                $page = 1;
                while (true) {
                    try {
                        $pageText = $pdf
                            ->setOptions(['layout', "f $page", "l $page", "eol unix"])
                            ->text();
                        $lines = explode("\n", $pageText);
                        for ($line = 8; $line < 58; $line += 5) {
                            $week = $this->parseLine($lines[$line], $lines[$line + 1], $lines[$line + 2]);
                            $weeks->put($week->getNumber(), $week);
                        }
                        if ($page === 1) {
                            preg_match('#Rota Start: Sun ([0-9/]+)#', $lines[1], $matches);
                            $startDate = Carbon::createFromFormat('d/m/y', $matches[1]);
                        }
                    } catch (CouldNotExtractText $e) {
                        break;
                    }
                    $page++;
                }

                return new RotaDTO($startDate, $weeks);
            });
        }

        return $this->rota;
    }

    private function parseLine(string $firstLine, string $secondLine, string $thirdLine): Week
    {
        $days = explode('|', $firstLine);
        $week = (int) trim($days[1]);
        if (Str::startsWith(trim($days[2]), '** Leave Cover')) {
            return Week::createLeaveCoverWeek($week);
        }

        $starts = explode('|', $secondLine);
        $ends = explode('|', $thirdLine);

        $duties = new Collection();
        for ($day = 2; $day <= 8; $day++) {
            $carbonDay = self::DAYS[$day];
            $number = trim($days[$day]);
            if ($number === 'R') {
                $duties->put($carbonDay, Duty::createRestDayDuty($carbonDay));
                continue;
            }

            $start = trim($starts[$day]);
            $end = trim($ends[$day]);
            $duties->put($carbonDay, Duty::createDuty($carbonDay, $number, $start, $end));
        }

        return Week::createWeekWithDuties($week, $duties);
    }
}
