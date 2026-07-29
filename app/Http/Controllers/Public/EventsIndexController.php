<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;

class EventsIndexController extends Controller
{
    public function index()
    {
        $events = EventConfig::orderByDesc('is_active')
            ->orderByDesc('event_start_date')
            ->get();

        return view('public.events-index', compact('events'));
    }
}
