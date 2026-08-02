<?php

namespace App\Services;

use App\Models\EventConfig;
use App\Models\Project;

class EvaluationService
{
    /**
     * Recalcule la moyenne, le classement et le statut de sélection de tous les projets
     * soumis, à partir des notes soumises. Les projets strictement à égalité de score au
     * niveau de la coupure du quota sont marqués "tied_pending" (ni inclus ni exclus
     * automatiquement) : la décision finale revient au comité de délibération, sur la base
     * des critères de départage.
     */
    public function recalculateRanking(?EventConfig $event = null): array
    {
        $event = $event ?? EventConfig::active();
        $quota = $event?->selection_quota;

        $projects = Project::where('status', 'submitted')->forEvent($event)->with('scores.details')->get();

        foreach ($projects as $project) {
            $submitted = $project->scores->where('status', 'submitted');
            $project->average_score = $submitted->isEmpty()
                ? null
                : round($submitted->map(fn ($s) => $s->totalPoints())->avg(), 2);
        }

        $ranked = $projects->filter(fn ($p) => $p->average_score !== null)
            ->sortByDesc('average_score')
            ->values();

        $rank = 0;
        $previousScore = null;
        foreach ($ranked as $i => $project) {
            if ($project->average_score !== $previousScore) {
                $rank = $i + 1;
            }
            $project->rank_position = $rank;
            $previousScore = $project->average_score;
        }

        foreach ($projects->filter(fn ($p) => $p->average_score === null) as $project) {
            $project->rank_position = null;
        }

        $cutoffScore = null;
        $cutoffGroupCount = 0;
        if ($quota && $ranked->count() >= $quota) {
            $cutoffScore = $ranked[$quota - 1]->average_score;
            $cutoffGroupCount = $ranked->filter(fn ($p) => $p->average_score == $cutoffScore)->count();
        }

        foreach ($ranked as $project) {
            // Une décision manuelle déjà actée sur un ex-aequo n'est jamais réécrasée par un
            // recalcul ultérieur (le score/rang, eux, restent à jour).
            if ($project->evaluation_decided_at) {
                continue;
            }
            if (!$quota) {
                $project->evaluation_status = 'pending';
            } elseif ($cutoffScore === null || $project->average_score > $cutoffScore) {
                $project->evaluation_status = 'included';
            } elseif ($project->average_score == $cutoffScore) {
                $project->evaluation_status = $cutoffGroupCount > 1 ? 'tied_pending' : 'included';
            } else {
                $project->evaluation_status = 'excluded';
            }
        }

        foreach ($projects->filter(fn ($p) => $p->average_score === null) as $project) {
            $project->evaluation_status = 'pending';
        }

        foreach ($projects as $project) {
            $project->evaluation_finalized_at = now();
            $project->save();
        }

        return [
            'total'        => $projects->count(),
            'scored'       => $ranked->count(),
            'included'     => $ranked->where('evaluation_status', 'included')->count(),
            'excluded'     => $ranked->where('evaluation_status', 'excluded')->count(),
            'tied_pending' => $ranked->where('evaluation_status', 'tied_pending')->count(),
        ];
    }
}
