<?php

namespace App\Http\Controllers;

use App\Services\Rota;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenerateEventsController extends Controller
{
    public function getForm(Rota $service): Response
    {
        $rotaStartDate = $service->getRota()->getStart();
        $firstWeek = $service->getRota()->getWeek(1);

        return response()->view('generate-events', compact('rotaStartDate', 'firstWeek'));
    }

    public function generateEvents(Request $request)
    {
        $this->validate($request, [
            'start-date' => 'required|date',
            'position' => 'required|int|min:1',
            'end-date' => 'required|date|after:start-date',
        ], [
            'start-date.required' => 'Please enter a start date.',
            'start-date.date' => 'Please enter a valid date',
            'position.int' => 'The position must be a positive number.',
            'position.min' => 'The position must be a positive number.',
            'end-date.required' => 'Please enter an end date.',
            'end-date.date' => 'Please enter a valid date',
            'end-date.after' => 'The end date must be after the start date',
        ]);

        var_dump($request->input());
    }
}
