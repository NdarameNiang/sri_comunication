<?php

namespace App\Http\Controllers\PointFocal;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user         = Auth::user();
        $structureIds = $user->structures()->pluck('structures.id');
        $structures   = $user->structures()->orderBy('acronym')->get();

        $search      = $request->get('search', '');
        $filterSt    = $request->get('structure_id', '');
        $filterStatus= $request->get('status', '');

        $query = ProjectAssignment::whereIn('project_assignments.structure_id', $structureIds)
            ->with(['porteur', 'structure', 'project'])
            ->join('users as porteur', 'project_assignments.porteur_id', '=', 'porteur.id')
            ->select('project_assignments.*');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('porteur.name',  'like', "%{$search}%")
                  ->orWhere('porteur.phone', 'like', "%{$search}%")
                  ->orWhere('porteur.email', 'like', "%{$search}%")
                  ->orWhere('project_assignments.title', 'like', "%{$search}%");
            });
        }

        if ($filterSt) {
            $query->where('project_assignments.structure_id', (int) $filterSt);
        }

        if ($filterStatus === 'not_started') {
            $query->doesntHave('project');
        } elseif ($filterStatus === 'draft') {
            $query->whereHas('project', fn($q) => $q->where('status', 'draft'));
        } elseif ($filterStatus === 'submitted') {
            $query->whereHas('project', fn($q) => $q->where('status', 'submitted'));
        }

        $assignments = $query->latest('project_assignments.created_at')->paginate(20)->withQueryString();

        // Stats globales (sans filtres)
        $base = ProjectAssignment::whereIn('structure_id', $structureIds);
        $stats = [
            'total'       => (clone $base)->count(),
            'not_started' => (clone $base)->doesntHave('project')->count(),
            'draft'       => (clone $base)->whereHas('project', fn($q) => $q->where('status', 'draft'))->count(),
            'submitted'   => (clone $base)->whereHas('project', fn($q) => $q->where('status', 'submitted'))->count(),
        ];

        return view('point-focal.dashboard', compact(
            'assignments', 'stats', 'structures',
            'search', 'filterSt', 'filterStatus'
        ));
    }

    public function showProject(Project $project)
    {
        $project->load(['porteur', 'structure', 'assignment', 'collaborators']);
        return view('point-focal.projects.show', compact('project'));
    }
}
