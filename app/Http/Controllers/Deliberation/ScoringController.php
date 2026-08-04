<?php

namespace App\Http\Controllers\Deliberation;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use App\Models\ProjectScore;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoringController extends Controller
{
    /**
     * Un événement peut ne pas avoir de phase d'évaluation/sélection (ex : simple inscription
     * sans jury) — la notation est alors entièrement inaccessible, 404 comme les autres
     * fonctionnalités optionnelles par événement (allow_public_submission, etc.).
     */
    private function ensureEvaluationEnabled(): void
    {
        abort_unless(EventConfig::active()?->evaluationEnabled(), 404);
    }

    /**
     * En mode « individuelle », chaque évaluateur a son propre ProjectScore (clé project_id +
     * evaluator_id). En mode « globale », un seul ProjectScore existe par projet — la clé de
     * recherche omet l'évaluateur pour que tout membre du comité retrouve/modifie la même
     * note partagée plutôt que d'en créer une nouvelle.
     */
    private function scoreKey(EventConfig $event, Project $project): array
    {
        $key = ['project_id' => $project->id];
        if (!$event->isGlobalDeliberation()) {
            $key['evaluator_id'] = Auth::id();
        }
        return $key;
    }

    public function index(Request $request)
    {
        $this->ensureEvaluationEnabled();

        $event = EventConfig::active();

        $query = Project::where('status', 'submitted')
            ->forEvent($event)
            ->with(['porteur', 'structure', 'assignment'])
            ->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('assignment', fn($a) => $a->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('porteur', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhere('responsable_nom', 'like', "%{$search}%");
            });
        }

        if ($structureId = $request->get('structure_id')) {
            $query->where('structure_id', $structureId);
        }

        $allScores = $event->isGlobalDeliberation()
            ? ProjectScore::with('evaluator')->get()->keyBy('project_id')
            : ProjectScore::where('evaluator_id', Auth::id())->get()->keyBy('project_id');

        if ($notation = $request->get('notation')) {
            $scoredIds    = $allScores->filter(fn($s) => $s->isSubmitted())->keys()->toArray();
            $draftIds     = $allScores->filter(fn($s) => !$s->isSubmitted())->keys()->toArray();
            $allScoredIds = $allScores->keys()->toArray();
            if ($notation === 'done')    $query->whereIn('id', $scoredIds);
            if ($notation === 'draft')   $query->whereIn('id', $draftIds);
            if ($notation === 'pending') $query->whereNotIn('id', $allScoredIds);
        }

        $projects = $query->paginate(20)->withQueryString();

        $myScores = $allScores;
        $isGlobal = $event->isGlobalDeliberation();

        $structures = Structure::whereIn('id',
            Project::where('status', 'submitted')->forEvent($event)->distinct()->pluck('structure_id')
        )->orderBy('name')->get();

        return view('deliberation.scoring.index', compact('projects', 'myScores', 'isGlobal', 'structures'));
    }

    public function show(Project $project)
    {
        $this->ensureEvaluationEnabled();

        if (!$project->isSubmitted()) {
            abort(404);
        }

        $event  = EventConfig::active();
        $rubric = $event?->rubricFor($project->submission_template);
        $rubric?->load('criteria');

        if (!$rubric || $rubric->criteria->isEmpty()) {
            return back()->with('error', 'Aucune grille d\'évaluation n\'est configurée pour ce format de soumission.');
        }

        $score = ProjectScore::with(['details', 'evaluator'])
            ->firstOrNew($this->scoreKey($event, $project));

        $existingPoints = $score->exists
            ? $score->details->pluck('points', 'evaluation_criterion_id')
            : collect();

        $isGlobal = $event->isGlobalDeliberation();

        return view('deliberation.scoring.show', compact('project', 'rubric', 'score', 'existingPoints', 'isGlobal'));
    }

    public function store(Request $request, Project $project)
    {
        $this->ensureEvaluationEnabled();

        if (!$project->isSubmitted()) {
            abort(404);
        }

        $event  = EventConfig::active();
        $rubric = $event?->rubricFor($project->submission_template);
        abort_if(!$rubric, 404);
        $rubric->load('criteria');

        $rules = ['points' => 'required|array'];
        foreach ($rubric->criteria as $criterion) {
            $rules["points.{$criterion->id}"] = "required|numeric|min:0|max:{$criterion->max_points}";
        }
        $data = $request->validate($rules);

        $submit = $request->input('action') === 'submit';

        DB::transaction(function () use ($event, $project, $data, $submit) {
            // En mode globale, evaluator_id trace le dernier membre à avoir soumis/modifié la
            // note partagée (utile à l'affichage), pas une note qui lui est propre.
            $score = ProjectScore::updateOrCreate(
                $this->scoreKey($event, $project),
                [
                    'evaluator_id' => Auth::id(),
                    'status'       => $submit ? 'submitted' : 'draft',
                    'submitted_at' => $submit ? now() : null,
                ]
            );

            foreach ($data['points'] as $criterionId => $points) {
                $score->details()->updateOrCreate(
                    ['evaluation_criterion_id' => $criterionId],
                    ['points' => $points]
                );
            }
        });

        return redirect()->route('deliberation.scoring.index')
            ->with('success', $submit ? 'Notation soumise avec succès.' : 'Brouillon de notation enregistré.');
    }
}
