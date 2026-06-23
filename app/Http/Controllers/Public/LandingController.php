<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LandingController extends Controller
{
    public function show(string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $qrInscription   = QrCode::size(160)->generate(route('public.registration.show', $eventSlug));
        $qrQuestionnaire = $event->show_questionnaire
            ? QrCode::size(160)->generate(route('public.questionnaire.show', $eventSlug))
            : null;

        return view('public.landing', compact('event', 'qrInscription', 'qrQuestionnaire'));
    }
}
