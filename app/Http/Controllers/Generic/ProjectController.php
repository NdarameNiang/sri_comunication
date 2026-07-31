<?php

namespace App\Http\Controllers\Generic;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Visualisation en lecture seule, ouverte à toute capacité "projects.viewAll" —
     * historique (point focal, comité, direction) ou attribuée via un rôle créé
     * depuis le générateur de rôles.
     */
    public function index()
    {
        $query = Project::where('status', 'submitted')
            ->with(['porteur', 'structure', 'assignment'])
            ->latest();

        if ($search = request('search')) {
            $query->whereHas('porteur', fn ($u) => $u
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }

        $projects = $query->paginate(20)->withQueryString();

        return view('generic.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $project->load(['porteur', 'structure', 'assignment', 'collaborators']);
        return view('point-focal.projects.show', compact('project'));
    }
}
