<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Structure;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'submitted')
            ->with(['porteur', 'structure', 'assignment'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total'    => Project::where('status', 'submitted')->count(),
            'selected' => Project::where('selected', true)->count(),
            'sent'     => Project::whereNotNull('email_sent_at')->count(),
        ];

        $structures = Structure::withCount([
            'projects as submitted_count' => fn($q) => $q->where('status', 'submitted'),
            'projects as selected_count'  => fn($q) => $q->where('selected', true),
        ])->get();

        return view('comite.dashboard', compact('projects', 'stats', 'structures'));
    }

    public function show(Project $project)
    {
        $project->load(['porteur', 'structure', 'assignment', 'collaborators']);
        return view('comite.projects.show', compact('project'));
    }
}
