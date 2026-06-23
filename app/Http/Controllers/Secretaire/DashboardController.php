<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Questionnaire;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        $event = EventConfig::active();

        $stats = [
            'inscriptions'       => $event ? Registration::where('event_config_id', $event->id)->count() : 0,
            'presences'          => $event ? Registration::where('event_config_id', $event->id)->where('presence_confirmee', true)->count() : 0,
            'questionnaires'     => $event ? Questionnaire::where('event_config_id', $event->id)->count() : 0,
        ];

        $recentRegistrations = $event
            ? Registration::where('event_config_id', $event->id)->latest()->take(5)->get()
            : collect();

        return view('secretaire.dashboard', compact('event', 'stats', 'recentRegistrations'));
    }
}
