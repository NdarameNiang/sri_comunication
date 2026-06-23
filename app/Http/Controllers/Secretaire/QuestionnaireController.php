<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $event = EventConfig::active();
        $query = Questionnaire::query();

        if ($event) {
            $query->where('event_config_id', $event->id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $questionnaires = $query->latest()->paginate(20)->withQueryString();

        $moyennes = $event ? [
            'organisation' => round(Questionnaire::where('event_config_id', $event->id)->avg('note_organisation'), 1),
            'contenu'      => round(Questionnaire::where('event_config_id', $event->id)->avg('note_contenu'), 1),
            'logistique'   => round(Questionnaire::where('event_config_id', $event->id)->avg('note_logistique'), 1),
            'globale'      => round(Questionnaire::where('event_config_id', $event->id)->avg('note_globale'), 1),
        ] : null;

        return view('secretaire.questionnaires.index', compact('questionnaires', 'event', 'moyennes'));
    }

    public function show(Questionnaire $questionnaire)
    {
        return view('secretaire.questionnaires.show', compact('questionnaire'));
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->delete();
        return redirect()->route('secretaire.questionnaires.index')->with('success', 'Questionnaire supprimé.');
    }
}
