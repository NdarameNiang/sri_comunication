<?php

namespace App\Http\Controllers\PorteurProjet;

use App\Http\Controllers\Controller;
use App\Mail\SubmissionConfirmationMail;
use App\Models\EventConfig;
use App\Models\FormOption;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    private function activeEvent(): ?EventConfig
    {
        return EventConfig::active();
    }

    public function create(ProjectAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->project) {
            return redirect()->route('porteur.projects.edit', $assignment->project->id);
        }

        $event = $this->activeEvent();
        if ($event && !$event->isSubmissionOpen()) {
            return redirect()->route('porteur.dashboard')
                ->with('error', $this->submissionBlockMessage($event));
        }

        return view('porteur.projects.form', [
            'assignment'      => $assignment,
            'project'         => null,
            'formOptions'     => $this->loadFormOptions(),
            'collaboratorRoles' => FormOption::forGroup('collaborator_role'),
        ]);
    }

    public function store(Request $request, ProjectAssignment $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->project) {
            return redirect()->route('porteur.projects.edit', $assignment->project->id);
        }

        $event = $this->activeEvent();
        if ($event && !$event->isSubmissionOpen()) {
            return redirect()->route('porteur.dashboard')->with('error', $this->submissionBlockMessage($event));
        }

        $data = $this->validateForm($request);
        $data['assignment_id'] = $assignment->id;
        $data['porteur_id']    = Auth::id();
        $data['structure_id']  = $assignment->structure_id;
        $data['status']        = 'draft';

        $project = Project::create($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []));

        $assignment->update(['status' => 'submitted']);

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet enregistré avec succès.');
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);

        $event = $this->activeEvent();
        if ($project->isSubmitted()) {
            return redirect()->route('porteur.projects.show', $project->id);
        }

        if ($event && !$event->isSubmissionOpen()) {
            return redirect()->route('porteur.dashboard')->with('error', $this->submissionBlockMessage($event));
        }

        $project->load('collaborators');

        return view('porteur.projects.form', [
            'assignment'        => $project->assignment,
            'project'           => $project,
            'formOptions'       => $this->loadFormOptions(),
            'collaboratorRoles' => FormOption::forGroup('collaborator_role'),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        if ($project->isSubmitted()) {
            return back()->with('error', 'Ce projet a déjà été soumis et ne peut plus être modifié.');
        }

        $event = $this->activeEvent();
        if ($event && !$event->isSubmissionOpen()) {
            return redirect()->route('porteur.dashboard')->with('error', $this->submissionBlockMessage($event));
        }

        $data = $this->validateForm($request);
        $project->update($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []));

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function submit(Project $project)
    {
        $this->authorizeProject($project);

        if ($project->isSubmitted()) {
            return back()->with('error', 'Ce projet est déjà soumis.');
        }

        $event = $this->activeEvent();
        if ($event && !$event->isSubmissionOpen()) {
            return redirect()->route('porteur.dashboard')->with('error', $this->submissionBlockMessage($event));
        }

        $project->update(['status' => 'submitted']);
        $project->assignment->update(['status' => 'submitted']);

        try {
            Mail::to($project->porteur->email)->send(new SubmissionConfirmationMail($project->porteur, $project->load('collaborators', 'assignment', 'structure')));
        } catch (\Exception) {}

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet soumis officiellement. Un email de confirmation vous a été envoyé. Merci !');
    }

    public function show(Project $project)
    {
        $this->authorizeProject($project);
        $project->load('collaborators', 'assignment', 'structure');
        return view('porteur.projects.show', compact('project'));
    }

    private function authorizeAssignment(ProjectAssignment $assignment): void
    {
        if ($assignment->porteur_id !== Auth::id()) abort(403);
    }

    private function authorizeProject(Project $project): void
    {
        if ($project->porteur_id !== Auth::id()) abort(403);
    }

    private function submissionBlockMessage(EventConfig $event): string
    {
        return match($event->submissionStatus()) {
            'not_open' => 'La période de soumission n\'est pas encore ouverte. Elle débutera le ' . $event->submission_open_at?->format('d/m/Y à H:i') . '.',
            'closed'   => 'La période de soumission est clôturée depuis le ' . $event->submission_close_at?->format('d/m/Y à H:i') . '. Aucune soumission n\'est plus acceptée.',
            default    => 'La soumission est actuellement fermée.',
        };
    }

    private function loadFormOptions(): array
    {
        return [
            'scientific_domain'   => FormOption::forGroup('scientific_domain'),
            'project_type'        => FormOption::forGroup('project_type'),
            'maturity_level'      => FormOption::forGroup('maturity_level'),
            'protection_type'     => FormOption::forGroup('protection_type'),
            'valorisation_type'   => FormOption::forGroup('valorisation_type'),
            'impact_type'         => FormOption::forGroup('impact_type'),
            'presentation_format' => FormOption::forGroup('presentation_format'),
        ];
    }

    private function saveCollaborators(Project $project, array $collaborateurs): void
    {
        $project->collaborators()->delete();
        foreach ($collaborateurs as $collab) {
            if (!empty(trim($collab['nom'] ?? ''))) {
                $project->collaborators()->create([
                    'nom'               => $collab['nom'] ?? '',
                    'prenom'            => $collab['prenom'] ?? null,
                    'email'             => $collab['email'] ?? null,
                    'telephone'         => $collab['telephone'] ?? null,
                    'institution'       => $collab['institution'] ?? null,
                    'role_collaborateur'=> $collab['role'] ?? null,
                ]);
            }
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
            'project_types.*'      => 'string',
            'summary'              => 'required|string',
            'problematic'          => 'required|string',
            'solution'             => 'required|string',
            'results'              => 'nullable|string',
            'maturity_level'       => 'nullable|string',
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
