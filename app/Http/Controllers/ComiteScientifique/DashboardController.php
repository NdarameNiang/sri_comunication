<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use App\Models\ProjectScore;
use App\Models\Structure;

class DashboardController extends Controller
{
    public function index()
    {
        $event = EventConfig::active();

        $query = Project::where('status', 'submitted')
            ->forEvent($event)
            ->with(['porteur', 'structure', 'assignment'])
            ->latest();

        if ($search = request('search')) {
            $query->whereHas('porteur', fn($u) => $u
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }

        if ($structureId = request('structure_id')) {
            $query->where('structure_id', $structureId);
        }

        if ($decision = request('decision')) {
            if ($decision === 'selected')  $query->where('selected', true);
            if ($decision === 'pending')   $query->where('selected', false);
        }

        $projects = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Project::where('status', 'submitted')->forEvent($event)->count(),
            'selected' => Project::where('selected', true)->forEvent($event)->count(),
            'sent'     => Project::whereNotNull('email_sent_at')->forEvent($event)->count(),
        ];

        $evaluationEnabled = $event?->evaluationEnabled();
        $evaluationStats = null;

        if ($evaluationEnabled) {
            $submittedIds = Project::where('status', 'submitted')->forEvent($event)->pluck('id');

            $evaluationStats = [
                'scored'   => ProjectScore::whereIn('project_id', $submittedIds)->where('status', 'submitted')->distinct('project_id')->count('project_id'),
                'total'    => $submittedIds->count(),
                'isGlobal' => $event->isGlobalDeliberation(),
            ];
        }

        $structures = Structure::withCount([
            'projects as submitted_count' => fn($q) => $q->where('status', 'submitted')->forEvent($event),
            'projects as selected_count'  => fn($q) => $q->where('selected', true)->forEvent($event),
        ])->having('submitted_count', '>', 0)->get();

        return view('comite.dashboard', compact('projects', 'stats', 'structures', 'evaluationEnabled', 'evaluationStats'));
    }

    public function show(Project $project)
    {
        $project->load(['porteur', 'structure', 'assignment', 'collaborators', 'coPorteurs', 'approfondiDetails']);
        return view('comite.projects.show', compact('project'));
    }
}
