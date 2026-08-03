<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use App\Models\Structure;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationRankingController extends Controller
{
    /**
     * Un événement peut ne pas avoir de phase d'évaluation/sélection — le classement/
     * délibération est alors entièrement inaccessible, 404 comme les autres fonctionnalités
     * optionnelles par événement.
     */
    private function ensureEvaluationEnabled(): void
    {
        abort_unless(EventConfig::active()?->evaluationEnabled(), 404);
    }

    /**
     * Filtres communs à l'affichage et à l'export : structure, statut de délibération, et
     * plage de note moyenne — appliqués sur la même requête de base pour que l'export
     * corresponde toujours exactement à ce qui est affiché à l'écran.
     */
    private function filteredQuery(Request $request, ?EventConfig $event)
    {
        $query = Project::where('status', 'submitted')
            ->forEvent($event)
            ->with(['porteur', 'structure', 'assignment']);

        if ($structureId = $request->get('structure')) {
            $query->where('structure_id', $structureId);
        }

        if ($status = $request->get('status')) {
            $query->where('evaluation_status', $status);
        }

        if ($request->filled('score_min')) {
            $query->where('average_score', '>=', (float) $request->get('score_min'));
        }

        if ($request->filled('score_max')) {
            $query->where('average_score', '<=', (float) $request->get('score_max'));
        }

        return $query;
    }

    public function index(Request $request)
    {
        $this->ensureEvaluationEnabled();

        $event = EventConfig::active();

        $allProjects = Project::where('status', 'submitted')
            ->forEvent($event)
            ->get();

        $stats = [
            'total'        => $allProjects->count(),
            'scored'       => $allProjects->whereNotNull('average_score')->count(),
            'included'     => $allProjects->where('evaluation_status', 'included')->count(),
            'excluded'     => $allProjects->where('evaluation_status', 'excluded')->count(),
            'tied_pending' => $allProjects->where('evaluation_status', 'tied_pending')->count(),
        ];

        $projects = $this->filteredQuery($request, $event)
            ->orderByRaw('rank_position IS NULL, rank_position ASC')
            ->get();

        $structures = Structure::whereIn('id', $allProjects->pluck('structure_id')->unique())
            ->orderBy('name')
            ->get();

        $filters = $request->only(['structure', 'status', 'score_min', 'score_max']);

        return view('admin.evaluation.ranking.index', compact('event', 'projects', 'stats', 'structures', 'filters'));
    }

    /**
     * Export CSV du classement filtré — mêmes filtres que l'écran (structure, statut, plage
     * de note), pour permettre par exemple d'exporter uniquement les projets inclus d'une
     * structure donnée. Même pattern (streamDownload, BOM UTF-8) que
     * ComiteScientifique\ProjectController::export().
     */
    public function export(Request $request)
    {
        $this->ensureEvaluationEnabled();

        $event = EventConfig::active();
        $projects = $this->filteredQuery($request, $event)
            ->orderByRaw('rank_position IS NULL, rank_position ASC')
            ->get();

        $statusLabels = [
            'included'     => 'Inclus',
            'excluded'     => 'Exclu',
            'tied_pending' => 'Ex-æquo — en attente',
            'pending'      => 'Non classé',
        ];

        $filename = 'classement-' . ($event ? \Illuminate\Support\Str::slug($event->event_slug) : 'evenement')
            . '-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($projects, $statusLabels) {
            $fp = fopen('php://output', 'w');
            fputs($fp, "\xEF\xBB\xBF");
            fputcsv($fp, [
                'Rang', 'Projet', 'Structure', 'Porteur', 'Email porteur',
                'Score moyen', 'Statut', 'Décision manuelle', 'Décidé le',
            ], ';');

            foreach ($projects as $p) {
                fputcsv($fp, [
                    $p->rank_position ?? '',
                    $p->assignment?->title ?? '',
                    $p->structure?->acronym ?? $p->structure?->name ?? '',
                    $p->porteur?->name ?? '',
                    $p->porteur?->email ?? '',
                    $p->average_score ?? '',
                    $statusLabels[$p->evaluation_status] ?? $statusLabels['pending'],
                    $p->evaluation_decided_at ? 'Oui' : 'Non',
                    $p->evaluation_decided_at?->format('d/m/Y H:i') ?? '',
                ], ';');
            }
            fclose($fp);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function updateQuota(Request $request)
    {
        $this->ensureEvaluationEnabled();
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
        $this->ensureEvaluationEnabled();
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
        $this->ensureEvaluationEnabled();

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
        $this->ensureEvaluationEnabled();

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
