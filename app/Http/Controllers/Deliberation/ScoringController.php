<?php

namespace App\Http\Controllers\Deliberation;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use App\Models\ProjectScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoringController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'submitted')
            ->forEvent(EventConfig::active())
            ->with(['porteur', 'structure', 'assignment'])
            ->latest()
            ->paginate(20);

        $myScores = ProjectScore::where('evaluator_id', Auth::id())
            ->get()
            ->keyBy('project_id');

        return view('deliberation.scoring.index', compact('projects', 'myScores'));
    }

    public function show(Project $project)
    {
        if (!$project->isSubmitted()) {
            abort(404);
        }

        $event  = EventConfig::active();
        $rubric = $event?->rubricFor($project->submission_template);
        $rubric?->load('criteria');

        if (!$rubric || $rubric->criteria->isEmpty()) {
            return back()->with('error', 'Aucune grille d\'évaluation n\'est configurée pour ce format de soumission.');
        }

        $score = ProjectScore::with('details')
            ->firstOrNew(['project_id' => $project->id, 'evaluator_id' => Auth::id()]);

        $existingPoints = $score->exists
            ? $score->details->pluck('points', 'evaluation_criterion_id')
            : collect();

        return view('deliberation.scoring.show', compact('project', 'rubric', 'score', 'existingPoints'));
    }

    public function store(Request $request, Project $project)
    {
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

        DB::transaction(function () use ($project, $data, $submit) {
            $score = ProjectScore::updateOrCreate(
                ['project_id' => $project->id, 'evaluator_id' => Auth::id()],
                [
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
