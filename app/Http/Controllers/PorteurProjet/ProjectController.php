<?php

namespace App\Http\Controllers\PorteurProjet;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function create(ProjectAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->project) {
            return redirect()->route('porteur.projects.edit', $assignment->project->id);
        }

        return view('porteur.projects.form', [
            'assignment' => $assignment,
            'project'    => null,
        ]);
    }

    public function store(Request $request, ProjectAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->project) {
            return redirect()->route('porteur.projects.edit', $assignment->project->id);
        }

        $data = $this->validateForm($request);
        $data['assignment_id'] = $assignment->id;
        $data['porteur_id']    = Auth::id();
        $data['structure_id']  = $assignment->structure_id;
        $data['status']        = 'draft';

        Project::create($data);

        $assignment->update(['status' => 'submitted']);

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet enregistré avec succès.');
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);

        return view('porteur.projects.form', [
            'assignment' => $project->assignment,
            'project'    => $project,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        if ($project->isSubmitted()) {
            return back()->with('error', 'Ce projet a déjà été soumis et ne peut plus être modifié.');
        }

        $data = $this->validateForm($request);
        $project->update($data);

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function submit(Project $project)
    {
        $this->authorizeProject($project);

        if ($project->isSubmitted()) {
            return back()->with('error', 'Ce projet est déjà soumis.');
        }

        $project->update(['status' => 'submitted']);
        $project->assignment->update(['status' => 'submitted']);

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet soumis officiellement. Merci pour votre participation !');
    }

    private function authorizeAssignment(ProjectAssignment $assignment): void
    {
        if ($assignment->porteur_id !== Auth::id()) {
            abort(403);
        }
    }

    private function authorizeProject(Project $project): void
    {
        if ($project->porteur_id !== Auth::id()) {
            abort(403);
        }
    }

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'responsable_nom'      => 'required|string|max:255',
            'contact_email'        => 'required|email',
            'contact_phone'        => 'nullable|string|max:20',
            'scientific_domain'    => 'required|string|max:255',
            'project_types'        => 'required|array|min:1',
            'project_types.*'      => 'in:recherche,innovation,prototype,solution_appliquee',
            'summary'              => 'required|string',
            'problematic'          => 'required|string',
            'solution'             => 'required|string',
            'results'              => 'nullable|string',
            'maturity_level'       => 'nullable|in:prototype,teste,deploye',
            'protection_types'     => 'nullable|array',
            'protection_types.*'   => 'string',
            'protection_autres'    => 'nullable|string|max:500',
            'valorisation_types'   => 'nullable|array',
            'valorisation_types.*' => 'string',
            'valorisation_autres'  => 'nullable|string|max:500',
            'impact_types'         => 'nullable|array',
            'impact_types.*'       => 'string',
            'presentation_formats' => 'nullable|array',
            'presentation_formats.*'=> 'string',
            'presentation_autres'  => 'nullable|string|max:500',
            'logistic_needs'       => 'nullable|string',
        ]);
    }
}
