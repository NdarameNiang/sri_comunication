<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationRankingController extends Controller
{
    public function index()
    {
        $event = EventConfig::active();

        $projects = Project::where('status', 'submitted')
            ->forEvent($event)
            ->with(['porteur', 'structure', 'assignment'])
            ->orderByRaw('rank_position IS NULL, rank_position ASC')
            ->get();

        $stats = [
            'total'        => $projects->count(),
            'scored'       => $projects->whereNotNull('average_score')->count(),
            'included'     => $projects->where('evaluation_status', 'included')->count(),
            'excluded'     => $projects->where('evaluation_status', 'excluded')->count(),
            'tied_pending' => $projects->where('evaluation_status', 'tied_pending')->count(),
        ];

        return view('admin.evaluation.ranking.index', compact('event', 'projects', 'stats'));
    }

    public function updateQuota(Request $request)
    {
        $event = EventConfig::active();
        abort_if(!$event, 404, 'Aucun événement actif.');

        $data = $request->validate([
            'selection_quota' => 'nullable|integer|min:1',
        ]);

        $event->update(['selection_quota' => $data['selection_quota'] ?? null]);

        return back()->with('success', 'Quota de sélection mis à jour.');
    }

    public function recalculate(EvaluationService $service)
    {
        $event = EventConfig::active();
        abort_if(!$event, 404, 'Aucun événement actif.');

        $stats = $service->recalculateRanking($event);

        return back()->with('success', sprintf(
            'Classement recalculé : %d projet(s) noté(s), %d inclus, %d exclus, %d en attente de décision (ex-æquo).',
            $stats['scored'], $stats['included'], $stats['excluded'], $stats['tied_pending']
        ));
    }

    public function resolveTie(Request $request, Project $project)
    {
        if (!$project->isEvaluationTiedPending()) {
            return back()->with('error', 'Ce projet n\'est pas en attente de décision.');
        }

        $data = $request->validate([
            'decision' => 'required|in:included,excluded',
        ]);

        $project->update([
            'evaluation_status'      => $data['decision'],
            'evaluation_decided_by'  => Auth::id(),
            'evaluation_decided_at'  => now(),
        ]);

        return back()->with('success', 'Décision enregistrée pour le projet « ' . ($project->assignment?->title ?? $project->id) . ' ».');
    }

    /**
     * Traduit evaluation_status='included'/'excluded' vers les colonnes selected/selected_at/
     * selected_by déjà utilisées par ComiteScientifique\SelectionController::sendEmails(), sans
     * modifier ce pipeline existant. Les projets dont l'email a déjà été envoyé sont ignorés
     * (même garde que le bascule manuel existant).
     */
    public function applyToSelection()
    {
        $projects = Project::where('status', 'submitted')
            ->forEvent(EventConfig::active())
            ->whereIn('evaluation_status', ['included', 'excluded'])
            ->whereNull('email_sent_at')
            ->get();

        $applied = 0;
        foreach ($projects as $project) {
            $shouldBeSelected = $project->evaluation_status === 'included';
            if ((bool) $project->selected === $shouldBeSelected) {
                continue;
            }

            $project->update([
                'selected'    => $shouldBeSelected,
                'selected_at' => $shouldBeSelected ? now() : null,
                'selected_by' => $shouldBeSelected ? Auth::id() : null,
            ]);
            $applied++;
        }

        return back()->with('success', "{$applied} projet(s) mis à jour dans la sélection à partir du classement.");
    }
}
