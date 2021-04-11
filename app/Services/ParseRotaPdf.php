<?php

namespace App\Services;

use App\DTOs\Shift;
use App\DTOs\Week;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\PdfToText\Exceptions\CouldNotExtractText;
use Spatie\PdfToText\Pdf;

class ParseRotaPdf
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

    public function run(): Collection
    {
        $rotas = new Collection();
        $page = 1;
        while (true) {
            try {
                $pageText = (new Pdf())
                    ->setPdf(resource_path('rota.pdf'))
                    ->setOptions(['layout', "f $page", "l $page", "eol unix"])
                    ->text();
                $lines = explode("\n", $pageText);
                for ($line = 8; $line < 58; $line += 5) {
                    $days = explode('|', $lines[$line]);

                    $week = (int) trim($days[1]);
                    if (Str::startsWith(trim($days[2]), '** Leave Cover')) {
                        $rotas->put($week, new Week($week, true));
                        continue;
                    }

                    $starts = explode('|', $lines[$line + 1]);
                    $ends = explode('|', $lines[$line + 2]);

                    $shifts = new Collection();
                    for ($day = 2; $day <= 8; $day++) {
                        $carbonDay = self::DAYS[$day];
                        if (trim($days[$day]) === 'R') {
                            $shifts->put($carbonDay, new Shift($carbonDay, true));
                            continue;
                        }

                        $start = trim($starts[$day]);
                        $end = trim($ends[$day]);
                        $shifts->put($carbonDay, new Shift($carbonDay, false, $start, $end));
                    }
                    $rotas->put($week, new Week($week, false, $shifts));
                }
            } catch (CouldNotExtractText $e) {
                break;
            }
            $page++;
        }

        return $rotas;
    }
}
