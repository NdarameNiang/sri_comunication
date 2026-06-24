<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Structure;

class DashboardController extends Controller
{
    public function index()
    {
        $query = Project::where('status', 'submitted')
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
            'total'    => Project::where('status', 'submitted')->count(),
            'selected' => Project::where('selected', true)->count(),
            'sent'     => Project::whereNotNull('email_sent_at')->count(),
        ];

        $structures = Structure::withCount([
            'projects as submitted_count' => fn($q) => $q->where('status', 'submitted'),
            'projects as selected_count'  => fn($q) => $q->where('selected', true),
        ])->having('submitted_count', '>', 0)->get();

        return view('comite.dashboard', compact('projects', 'stats', 'structures'));
    }

    public function show(Project $project)
    {
        $project->load(['porteur', 'structure', 'assignment', 'collaborators']);
        return view('comite.projects.show', compact('project'));
    }
}
