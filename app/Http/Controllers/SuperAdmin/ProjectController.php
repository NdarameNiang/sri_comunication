<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\SubmissionConfirmationMail;
use App\Models\FormOption;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function index()
    {
        $query = ProjectAssignment::with('porteur', 'structure', 'project')->latest();

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('porteur', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('structure', fn($s) => $s->where('name', 'like', "%{$search}%")->orWhere('acronym', 'like', "%{$search}%"));
            });
        }

        if ($status = request('status')) {
            if ($status === 'pending') {
                $query->whereDoesntHave('project');
            } else {
                $query->whereHas('project', fn($p) => $p->where('status', $status));
            }
        }

        if ($porteur = request('porteur_id')) {
            $query->where('porteur_id', $porteur);
        }

        if ($structure = request('structure_id')) {
            $query->where('structure_id', $structure);
        }

        $assignments   = $query->paginate(25)->withQueryString();
        $porteurs      = User::where('role', 'porteur_projet')->orderBy('name')->get();
        $structures    = Structure::whereIn('id', ProjectAssignment::distinct()->pluck('structure_id'))->orderBy('name')->get();

        return view('superadmin.projects.index', compact('assignments', 'porteurs', 'structures'));
    }

    public function create()
    {
        $porteurs   = User::where('role', 'porteur_projet')->orderBy('name')->get();
        $structures = Structure::orderBy('name')->get();

        return view('superadmin.projects.create', compact('porteurs', 'structures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'porteur_id'   => 'required|exists:users,id',
            'structure_id' => 'required|exists:structures,id',
            'title'        => 'required|string|max:255',
        ]);

        ProjectAssignment::create([
            'porteur_id'   => $data['porteur_id'],
            'structure_id' => $data['structure_id'],
            'title'        => $data['title'],
            'status'       => 'pending',
        ]);

        return redirect()->route('superadmin.projects.index')
            ->with('success', 'Projet affecté au porteur avec succès.');
    }

    public function fill(ProjectAssignment $assignment)
    {
        $assignment->load('structure', 'porteur');

        if ($assignment->project) {
            return redirect()->route('superadmin.projects.edit', $assignment->project);
        }

        $project = null;
        $formOptions       = $this->loadFormOptions();
        $collaboratorRoles = FormOption::forGroup('collaborator_role');

        return view('superadmin.projects.fill', compact('assignment', 'project', 'formOptions', 'collaboratorRoles'));
    }

    public function storeFill(Request $request, ProjectAssignment $assignment)
    {
        if ($assignment->project) {
            return redirect()->route('superadmin.projects.edit', $assignment->project);
        }

        $data = $this->validateForm($request);
        $data['assignment_id'] = $assignment->id;
        $data['porteur_id']    = $assignment->porteur_id;
        $data['structure_id']  = $assignment->structure_id;
        $data['status']        = 'draft';

        $project = Project::create($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []));

        return redirect()->route('superadmin.projects.edit', $project)
            ->with('success', 'Projet créé. Vous pouvez maintenant le soumettre.');
    }

    public function edit(Project $project)
    {
        $project->load('collaborators', 'assignment.structure', 'structure');

        $formOptions       = $this->loadFormOptions();
        $collaboratorRoles = FormOption::forGroup('collaborator_role');

        return view('superadmin.projects.edit', compact('project', 'formOptions', 'collaboratorRoles'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validateForm($request);
        $project->update($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []));

        return redirect()->route('superadmin.projects.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function submit(Project $project)
    {
        if ($project->isSubmitted()) {
            return back()->with('warning', 'Ce projet est déjà soumis.');
        }

        $project->update(['status' => 'submitted']);
        $project->assignment?->update(['status' => 'submitted']);

        try {
            $project->load('collaborators', 'assignment', 'structure');

            $primaryEmail = !empty($project->email_professionnel)
                ? $project->email_professionnel
                : $project->porteur->email;

            $mailer = Mail::to($primaryEmail);

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

        return redirect()->route('superadmin.projects.index')
            ->with('success', 'Projet soumis officiellement. Email de confirmation envoyé au porteur.');
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
        foreach ($collaborateurs as $c) {
            if (!empty(trim($c['nom'] ?? ''))) {
                $project->collaborators()->create([
                    'nom'                => $c['nom'] ?? '',
                    'prenom'             => $c['prenom'] ?? null,
                    'email'              => $c['email'] ?? null,
                    'telephone'          => $c['telephone'] ?? null,
                    'institution'        => $c['institution'] ?? null,
                    'role_collaborateur' => $c['role'] ?? null,
                ]);
            }
        }
    }

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'responsable_nom'        => 'required|string|max:255',
            'contact_email'          => 'required|email|max:255',
            'email_professionnel'    => 'nullable|email|max:255',
            'contact_phone'          => 'nullable|string|max:30',
            'scientific_domain'      => 'required|string|max:255',
            'project_types'          => 'required|array|min:1',
            'project_types.*'        => 'string|max:100',
            'maturity_level'         => 'nullable|string|max:100',
            'summary'                => 'required|string|min:20',
            'problematic'            => 'required|string|min:20',
            'solution'               => 'required|string|min:20',
            'results'                => 'nullable|string',
            'protection_types'       => 'nullable|array',
            'protection_autres'      => 'nullable|string|max:500',
            'valorisation_types'     => 'nullable|array',
            'valorisation_autres'    => 'nullable|string|max:500',
            'impact_types'           => 'nullable|array',
            'presentation_formats'   => 'nullable|array',
            'presentation_autres'    => 'nullable|string|max:500',
            'logistic_needs'         => 'nullable|string',
            'collaborateurs'         => 'nullable|array|max:20',
            'collaborateurs.*.nom'   => 'nullable|string|max:255',
            'collaborateurs.*.prenom'=> 'nullable|string|max:255',
            'collaborateurs.*.email' => 'nullable|email|max:255',
            'collaborateurs.*.telephone' => 'nullable|string|max:30',
            'collaborateurs.*.institution' => 'nullable|string|max:255',
            'collaborateurs.*.role'  => 'nullable|string|max:100',
        ]);
    }
}
