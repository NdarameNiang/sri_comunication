<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationRubric;
use App\Models\EventConfig;
use Illuminate\Http\Request;

class EvaluationRubricController extends Controller
{
    public function index()
    {
        $event = EventConfig::active();

        if (!$event) {
            return view('admin.evaluation.rubrics.index', ['event' => null, 'rubrics' => collect()]);
        }

        EvaluationRubric::ensureDefaults($event);
        $rubrics = $event->evaluationRubrics()->with('criteria')->get()->keyBy('submission_template');

        return view('admin.evaluation.rubrics.index', compact('event', 'rubrics'));
    }

    public function edit(string $template)
    {
        $this->validateTemplate($template);
        $event = EventConfig::active();
        abort_if(!$event, 404, 'Aucun événement actif.');

        EvaluationRubric::ensureDefaults($event);
        $rubric = $event->rubricFor($template);
        $rubric->load('criteria');

        return view('admin.evaluation.rubrics.edit', compact('rubric', 'template'));
    }

    public function update(Request $request, string $template)
    {
        $this->validateTemplate($template);
        $event = EventConfig::active();
        abort_if(!$event, 404, 'Aucun événement actif.');

        $rubric = $event->rubricFor($template);
        abort_if(!$rubric, 404);

        if ($rubric->isLocked()) {
            return back()->with('error', 'Cette grille ne peut plus être modifiée : des notes ont déjà été soumises avec cette configuration.');
        }

        $data = $request->validate([
            'criteria'              => 'required|array|min:1',
            'criteria.*.label'      => 'required|string|max:255',
            'criteria.*.max_points' => 'required|integer|min:1|max:100',
        ]);

        $rubric->criteria()->delete();
        foreach ($data['criteria'] as $i => $criterion) {
            $rubric->criteria()->create([
                'label'      => $criterion['label'],
                'max_points' => $criterion['max_points'],
                'sort_order' => $i,
            ]);
        }

        return redirect()->route('evaluation.rubrics.index')
            ->with('success', 'Grille d\'évaluation mise à jour avec succès.');
    }

    private function validateTemplate(string $template): void
    {
        abort_unless(in_array($template, ['standard', 'approfondi'], true), 404);
    }
}
