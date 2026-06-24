<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;

class LandingController extends Controller
{
    public function show(string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $hasQuestionnaire = (bool) $event->show_questionnaire;

        return view('public.landing', compact('event', 'hasQuestionnaire'));
    }
}
