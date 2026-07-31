<?php

namespace App\Http\Controllers\PorteurProjet;

use App\Http\Controllers\Concerns\HandlesProjectSubmission;
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
    use HandlesProjectSubmission;

    private function activeEvent(): ?EventConfig
    {
        return EventConfig::active();
    }

    public function chooseTemplate(ProjectAssignment $assignment)
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

        return view('porteur.projects.choose-template', compact('assignment'));
    }

    public function create(Request $request, ProjectAssignment $assignment)
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

        $template = $request->query('template') === 'approfondi' ? 'approfondi' : 'standard';

        return view('porteur.projects.form', [
            'assignment'      => $assignment,
            'project'         => null,
            'template'        => $template,
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

        $template = $request->input('submission_template') === 'approfondi' ? 'approfondi' : 'standard';
        $data = $template === 'approfondi' ? $this->validateApprofondiForm($request) : $this->validateForm($request);
        $data['assignment_id']       = $assignment->id;
        $data['porteur_id']          = Auth::id();
        $data['structure_id']        = $assignment->structure_id;
        $data['status']              = 'draft';
        $data['submission_template'] = $template;

        $project = Project::create($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []), 'collaborateur');

        if ($template === 'approfondi') {
            $this->saveCollaborators($project, $request->input('co_porteurs', []), 'co_porteur');
            $this->saveApprofondiDetails($project, $request);
        }

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

        $project->load('collaborators', 'coPorteurs', 'approfondiDetails');

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

        $data = $project->isApprofondi() ? $this->validateApprofondiForm($request) : $this->validateForm($request);
        $project->update($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []), 'collaborateur');

        if ($project->isApprofondi()) {
            $this->saveCollaborators($project, $request->input('co_porteurs', []), 'co_porteur');
            $this->saveApprofondiDetails($project, $request);
        }

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
            $project->load('collaborators', 'assignment', 'structure');

            // Destinataire principal : email personnel si renseigné, sinon email UCAD
            $primaryEmail = !empty($project->email_professionnel)
                ? $project->email_professionnel
                : $project->porteur->email;

            $mailer = Mail::to($primaryEmail);

            // CC : collaborateurs ayant un email
            $collabEmails = $project->collaborators
                ->filter(fn($c) => !empty($c->email))
                ->map(fn($c) => $c->email)
                ->values()
                ->toArray();

            if (!empty($collabEmails)) {
                $mailer = $mailer->cc($collabEmails);
            }

            $mailer->send(new SubmissionConfirmationMail($project->porteur, $project));
        } catch (\Exception) {}

        return redirect()->route('porteur.dashboard')
            ->with('success', 'Projet soumis officiellement. Un email de confirmation vous a été envoyé. Merci !');
    }

    public function show(Project $project)
    {
        $this->authorizeProject($project);
        $project->load('collaborators', 'coPorteurs', 'approfondiDetails', 'assignment', 'structure');
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
}
