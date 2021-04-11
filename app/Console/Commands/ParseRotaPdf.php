<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseRotaPdf extends Command
{
    protected $signature = 'rota:parse';

    protected $description = 'This command parse the rota file and creates a Collection of Week objects';

    public function handle(\App\Services\ParseRotaPdf $service): int
    {
        try {
            print_r($service->run()->toArray());
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return 1;
        }

        return 0;
    }
}
