<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\SubmissionConfirmationMail;
use App\Models\EventConfig;
use App\Models\FormOption;
use App\Models\Project;
use App\Models\ProjectApprofondiDetail;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    public function index()
    {
        $event = EventConfig::active();

        $query = ProjectAssignment::with('porteur', 'structure', 'project')
            ->when($event, fn ($q) => $q->where('event_config_id', $event->id))
            ->latest();

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
        $event = EventConfig::active();
        if (!$event) {
            return back()->withErrors(['title' => 'Aucun événement actif — impossible de créer une affectation de projet.'])->withInput();
        }

        $data = $request->validate([
            'porteur_id'   => 'required|exists:users,id',
            'structure_id' => 'required|exists:structures,id',
            'title'        => 'required|string|max:255',
        ]);

        ProjectAssignment::create([
            'porteur_id'      => $data['porteur_id'],
            'structure_id'    => $data['structure_id'],
            'event_config_id' => $event->id,
            'title'           => $data['title'],
            'status'          => 'pending',
        ]);

        return redirect()->route('superadmin.projects.index')
            ->with('success', 'Projet affecté au porteur avec succès.');
    }

    public function chooseTemplate(ProjectAssignment $assignment)
    {
        $assignment->load('structure', 'porteur');

        if ($assignment->project) {
            return redirect()->route('superadmin.projects.edit', $assignment->project);
        }

        $adminMode = true;

        return view('porteur.projects.choose-template', compact('assignment', 'adminMode'));
    }

    public function fill(Request $request, ProjectAssignment $assignment)
    {
        $assignment->load('structure', 'porteur');

        if ($assignment->project) {
            return redirect()->route('superadmin.projects.edit', $assignment->project);
        }

        $project = null;
        $template = $request->query('template') === 'approfondi' ? 'approfondi' : 'standard';
        $formOptions       = $this->loadFormOptions();
        $collaboratorRoles = FormOption::forGroup('collaborator_role');

        return view('superadmin.projects.fill', compact('assignment', 'project', 'template', 'formOptions', 'collaboratorRoles'));
    }

    public function storeFill(Request $request, ProjectAssignment $assignment)
    {
        if ($assignment->project) {
            return redirect()->route('superadmin.projects.edit', $assignment->project);
        }

        $template = $request->input('submission_template') === 'approfondi' ? 'approfondi' : 'standard';
        $data = $template === 'approfondi' ? $this->validateApprofondiForm($request) : $this->validateForm($request);
        $data['assignment_id']       = $assignment->id;
        $data['porteur_id']          = $assignment->porteur_id;
        $data['structure_id']        = $assignment->structure_id;
        $data['status']              = 'draft';
        $data['submission_template'] = $template;

        $project = Project::create($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []), 'collaborateur');

        if ($template === 'approfondi') {
            $this->saveCollaborators($project, $request->input('co_porteurs', []), 'co_porteur');
            $this->saveApprofondiDetails($project, $request);
        }

        return redirect()->route('superadmin.projects.edit', $project)
            ->with('success', 'Projet créé. Vous pouvez maintenant le soumettre.');
    }

    public function edit(Project $project)
    {
        $project->load('collaborators', 'coPorteurs', 'approfondiDetails', 'assignment.structure', 'structure');

        $formOptions       = $this->loadFormOptions();
        $collaboratorRoles = FormOption::forGroup('collaborator_role');

        return view('superadmin.projects.edit', compact('project', 'formOptions', 'collaboratorRoles'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $project->isApprofondi() ? $this->validateApprofondiForm($request) : $this->validateForm($request);
        $project->update($data);
        $this->saveCollaborators($project, $request->input('collaborateurs', []), 'collaborateur');

        if ($project->isApprofondi()) {
            $this->saveCollaborators($project, $request->input('co_porteurs', []), 'co_porteur');
            $this->saveApprofondiDetails($project, $request);
        }

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

    private function saveCollaborators(Project $project, array $collaborateurs, string $category = 'collaborateur'): void
    {
        $relation = $category === 'co_porteur' ? $project->coPorteurs() : $project->collaborators();
        $relation->delete();
        foreach ($collaborateurs as $c) {
            if (!empty(trim($c['nom'] ?? ''))) {
                $relation->create([
                    'nom'                => $c['nom'] ?? '',
                    'prenom'             => $c['prenom'] ?? null,
                    'email'              => $c['email'] ?? null,
                    'telephone'          => $c['telephone'] ?? null,
                    'institution'        => $c['institution'] ?? null,
                    'role_collaborateur' => $c['role'] ?? null,
                    'category'           => $category,
                ]);
            }
        }
    }

    private function saveApprofondiDetails(Project $project, Request $request): void
    {
        $splitCsv = fn (?string $v) => $v ? array_values(array_filter(array_map('trim', explode(',', $v)))) : [];

        $project->approfondiDetails()->updateOrCreate(['project_id' => $project->id], [
            'laboratoire_nom'           => $request->input('laboratoire_nom'),
            'laboratoire_acronyme'      => $request->input('laboratoire_acronyme'),
            'laboratoire_site_web'      => $request->input('laboratoire_site_web'),
            'responsable_titre'         => $request->input('responsable_titre'),
            'responsable_fonction'      => $request->input('responsable_fonction'),
            'titre_complet'             => $request->input('titre_complet'),
            'acronyme_projet'           => $request->input('acronyme_projet'),
            'sous_domaines'             => $splitCsv($request->input('sous_domaines_text')),
            'mots_cles'                 => $splitCsv($request->input('mots_cles_text')),
            'date_demarrage'            => $request->input('date_demarrage'),
            'duree_prevue'              => $request->input('duree_prevue'),
            'contexte_etat_art'         => $request->input('contexte_etat_art'),
            'approche_methodologique'   => $request->input('approche_methodologique'),
            'caractere_innovant'        => $request->input('caractere_innovant'),
            'resultats_scientifiques'   => $request->input('resultats_scientifiques'),
            'resultats_techniques'      => $request->input('resultats_techniques'),
            'indicateurs_chiffres'      => $request->input('indicateurs_chiffres'),
            'trl_level'                 => $request->input('trl_level'),
            'voies_valorisation'        => $request->input('voies_valorisation', []),
            'propriete_intellectuelle'  => $request->input('propriete_intellectuelle'),
            'modele_economique'         => $request->input('modele_economique'),
            'partenariats_financement'  => $request->input('partenariats_financement'),
            'dimensions_impact'         => $request->input('dimensions_impact', []),
            'beneficiaires'             => $request->input('beneficiaires'),
            'indicateurs_impact'        => $request->input('indicateurs_impact'),
            'contribution_odd'          => $request->input('contribution_odd'),
            'pertinence_senegal_afrique'=> $request->input('pertinence_senegal_afrique'),
            'public_cible_vise'         => $request->input('public_cible_vise'),
            'supports_prevus'           => $request->input('supports_prevus'),
            'annexes_checklist'         => $request->input('annexes_checklist', []),
            'annexes_autres_texte'      => $request->input('annexes_autres_texte'),
        ]);
    }

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'responsable_nom'        => 'required|string|max:255',
            'contact_email'          => 'required|email|max:255',
            'email_professionnel'    => 'nullable|email|max:255',
            'contact_phone'          => 'nullable|string|max:30',
            'scientific_domain'      => 'required|string|max:255',
            'scientific_domain_autre'=> 'nullable|string|max:255',
            'project_types'          => 'required|array|min:1',
            'project_types.*'        => 'string|max:100',
            'project_types_autres'   => 'nullable|string|max:500',
            'maturity_level'         => 'nullable|string|max:100',
            'maturity_level_autre'   => 'nullable|string|max:255',
            'summary'                => 'required|string|min:20',
            'problematic'            => 'required|string|min:20',
            'solution'               => 'required|string|min:20',
            'results'                => 'nullable|string',
            'protection_types'       => 'nullable|array',
            'protection_autres'      => 'nullable|string|max:500',
            'valorisation_types'     => 'nullable|array',
            'valorisation_autres'    => 'nullable|string|max:500',
            'impact_types'           => 'nullable|array',
            'impact_types_autres'    => 'nullable|string|max:500',
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

    private function validateApprofondiForm(Request $request): array
    {
        $data = $request->validate([
            'responsable_nom'        => 'required|string|max:255',
            'contact_email'          => 'required|email|max:255',
            'email_professionnel'    => 'nullable|email|max:255',
            'contact_phone'          => 'nullable|string|max:30',
            'scientific_domain'      => 'required|string|max:255',
            'scientific_domain_autre'=> 'nullable|string|max:255',
            'project_types'          => 'required|array|min:1',
            'project_types.*'        => 'string|max:100',
            'project_types_autres'   => 'nullable|string|max:500',
            'summary'                => 'required|string|min:20',
            'problematic'            => 'required|string|min:20',
            'solution'               => 'required|string|min:20',
            'results'                => 'nullable|string',
            'protection_types'       => 'nullable|array',
            'protection_types.*'     => 'string|max:100',
            'protection_autres'      => 'nullable|string|max:500',
            'valorisation_types'     => 'nullable|array',
            'valorisation_types.*'   => 'string|max:100',
            'valorisation_autres'    => 'nullable|string|max:500',
            'presentation_formats'   => 'nullable|array',
            'presentation_formats.*' => 'string|max:100',
            'presentation_autres'    => 'nullable|string|max:500',
            'logistic_needs'         => 'nullable|string',

            'laboratoire_nom'                   => 'required|string|max:255',
            'laboratoire_acronyme'              => 'nullable|string|max:100',
            'laboratoire_site_web'              => 'nullable|url|max:255',
            'responsable_titre'                 => 'nullable|string|max:255',
            'responsable_fonction'              => 'nullable|string|max:255',
            'titre_complet'                     => 'required|string|max:500',
            'acronyme_projet'                   => 'nullable|string|max:100',
            'sous_domaines_text'                => 'nullable|string|max:500',
            'mots_cles_text'                    => 'nullable|string|max:500',
            'date_demarrage'                    => 'nullable|date',
            'duree_prevue'                      => 'nullable|string|max:100',
            'contexte_etat_art'                 => 'nullable|string',
            'approche_methodologique'           => 'nullable|string',
            'caractere_innovant'                => 'nullable|string',
            'resultats_scientifiques'           => 'nullable|string',
            'resultats_techniques'              => 'nullable|string',
            'indicateurs_chiffres'              => 'nullable|string',
            'trl_level'                         => 'nullable|string|max:100',
            'voies_valorisation'                => 'nullable|array',
            'voies_valorisation.*'              => 'string|max:100',
            'propriete_intellectuelle'          => 'nullable|string',
            'modele_economique'                 => 'nullable|string',
            'partenariats_financement'          => 'nullable|string',
            'dimensions_impact'                 => 'nullable|array',
            'dimensions_impact.*'               => 'string|max:100',
            'beneficiaires'                     => 'nullable|string',
            'indicateurs_impact'                => 'nullable|string',
            'contribution_odd'                  => 'nullable|string',
            'pertinence_senegal_afrique'         => 'nullable|string',
            'public_cible_vise'                 => 'nullable|string',
            'supports_prevus'                   => 'nullable|string',
            'annexes_checklist'                 => 'nullable|array',
            'annexes_checklist.*'               => 'string|max:100',
            'annexes_autres_texte'              => 'nullable|string|max:500',

            'co_porteurs'              => 'nullable|array|max:10',
            'co_porteurs.*.nom'        => 'nullable|string|max:255',
            'co_porteurs.*.prenom'     => 'nullable|string|max:255',
            'co_porteurs.*.email'      => 'nullable|email|max:255',
            'co_porteurs.*.institution'=> 'nullable|string|max:255',

            'collaborateurs'         => 'nullable|array|max:20',
            'collaborateurs.*.nom'   => 'nullable|string|max:255',
            'collaborateurs.*.prenom'=> 'nullable|string|max:255',
            'collaborateurs.*.email' => 'nullable|email|max:255',
            'collaborateurs.*.telephone' => 'nullable|string|max:30',
            'collaborateurs.*.institution' => 'nullable|string|max:255',
            'collaborateurs.*.role'  => 'nullable|string|max:100',
        ]);

        return collect($data)->except([
            'laboratoire_nom', 'laboratoire_acronyme', 'laboratoire_site_web',
            'responsable_titre', 'responsable_fonction',
            'titre_complet', 'acronyme_projet', 'sous_domaines_text', 'mots_cles_text',
            'date_demarrage', 'duree_prevue',
            'contexte_etat_art', 'approche_methodologique', 'caractere_innovant',
            'resultats_scientifiques', 'resultats_techniques', 'indicateurs_chiffres',
            'trl_level', 'voies_valorisation', 'propriete_intellectuelle',
            'modele_economique', 'partenariats_financement',
            'dimensions_impact', 'beneficiaires', 'indicateurs_impact',
            'contribution_odd', 'pertinence_senegal_afrique',
            'public_cible_vise', 'supports_prevus',
            'annexes_checklist', 'annexes_autres_texte',
            'co_porteurs', 'collaborateurs',
        ])->toArray();
    }
}
