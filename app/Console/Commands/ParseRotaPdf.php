<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseRotaPdf extends Command
{
    protected $signature = 'rota:parse';

    protected $description = 'This command parse the rota file and creates a Collection of Week objects';

    public function handle(\App\Services\Rota $service): int
    {
        try {
            $rota = $service->getRota();
            echo "Rota start: ". $rota->getStart()->format('Y-m-d');
            print_r($rota->getWeeks()->toArray());
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return 1;
        }

        return 0;
    }
}
