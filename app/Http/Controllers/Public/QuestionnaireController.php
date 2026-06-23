<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function show(string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();
        return view('public.questionnaire', compact('event'));
    }

    public function store(Request $request, string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'nom'               => 'nullable|string|max:255',
            'prenom'            => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'institution'       => 'nullable|string|max:255',
            'note_organisation' => 'required|integer|min:1|max:5',
            'note_contenu'      => 'required|integer|min:1|max:5',
            'note_logistique'   => 'required|integer|min:1|max:5',
            'note_globale'      => 'required|integer|min:1|max:5',
            'points_positifs'   => 'nullable|string',
            'points_amelioration'=> 'nullable|string',
            'suggestions'       => 'nullable|string',
            'recommanderait'    => 'nullable|boolean',
        ]);

        $data['event_config_id'] = $event->id;
        Questionnaire::create($data);

        return redirect()->route('public.questionnaire.show', $eventSlug)
            ->with('success', 'Merci pour votre retour ! Votre questionnaire a bien été soumis.');
    }
}
