<?php

namespace App\Http\Controllers;

use App\Services\Rota;
use Illuminate\Http\Response;

class GenerateEventsController extends Controller
{
    public function __invoke(Rota $service): Response
    {
        $rotaStartDate = $service->getRota()->getStart();
        $firstWeek = $service->getRota()->getWeek(1);
        $lastPosition = $service->getNumberOfPositions();

        return response()->view('generate-events', compact('rotaStartDate', 'firstWeek', 'lastPosition'));
    }
}
