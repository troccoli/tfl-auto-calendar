<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait Throttable
{
    public function throttle(Collection $collection, int $size, callable $callable, int $throttle = 1)
    {
        foreach ($collection->chunk($size) as $chunk) {
            $callable($chunk);
            sleep($throttle);
        }
    }
}
