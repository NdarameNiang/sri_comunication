<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\HandlesProjectSubmission;
use App\Http\Controllers\Controller;
use App\Mail\SubmissionConfirmationMail;
use App\Models\EventConfig;
use App\Models\FormOption;
use App\Models\Personnel;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Dépôt de projet entièrement public : aucun compte ni connexion n'est demandé au
 * soumissionnaire. L'identité est vérifiée (StudentCenter pour un étudiant, table
 * Personnel — fausses données en attendant l'API réelle — pour un PATS/PER), puis la suite
 * du dépôt (choix du template, remplissage, soumission) réutilise le formulaire existant
 * (HandlesProjectSubmission) via un accès par jeton plutôt que par session authentifiée.
 */
class ProjectSubmissionController extends Controller
{
    use HandlesProjectSubmission;

    private const SESSION_IDENTITY_KEY = 'public_submission_identity';

    /**
     * Vérifie uniquement que la fonctionnalité est activée pour l'événement (le toggle
     * admin) — 404 sinon, la page n'existe simplement pas. La période de soumission est
     * vérifiée séparément (submissionOpenOrFail) pour pouvoir afficher un message clair
     * plutôt qu'une erreur brute quand la fonctionnalité existe mais est temporairement
     * fermée.
     */
    private function event(string $eventSlug): EventConfig
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();
        abort_unless($event->allow_public_submission, 404);
        return $event;
    }

    private function submissionBlockMessage(EventConfig $event): string
    {
        return match ($event->submissionStatus()) {
            'not_open' => 'Le dépôt de projet n\'est pas encore ouvert. Il débutera le ' . $event->submission_open_at?->format('d/m/Y à H:i') . '.',
            'closed'   => 'La période de dépôt est clôturée depuis le ' . $event->submission_close_at?->format('d/m/Y à H:i') . '. Aucun dépôt n\'est plus accepté.',
            default    => 'Le dépôt de projet est actuellement fermé.',
        };
    }

    // ── Étape 1 : identification ────────────────────────────────────────────

    public function identify(string $eventSlug)
    {
        $event = $this->event($eventSlug);

        if (!$event->isSubmissionOpen()) {
            return view('public.project-submission-identify', [
                'event' => $event,
                'cycleOptions' => collect(),
                'closedMessage' => $this->submissionBlockMessage($event),
            ]);
        }

        $cycleOptions = FormOption::forGroup('population_category')
            ->filter(fn ($o) => str_starts_with($o->value, 'etudiant_'));

        return view('public.project-submission-identify', compact('event', 'cycleOptions'));
    }

    public function verify(Request $request, string $eventSlug)
    {
        $event = $this->event($eventSlug);
        if (!$event->isSubmissionOpen()) {
            return redirect()->route('public.project-submission.identify', $eventSlug)
                ->with('error', $this->submissionBlockMessage($event));
        }

        $data = $request->validate([
            'profile_type'   => 'required|in:etudiant,personnel',
            'cycle'          => 'required_if:profile_type,etudiant|nullable|in:etudiant_licence,etudiant_master,etudiant_doctorat',
            'cin'            => 'nullable|string|max:50',
            'numero_carte'   => 'required_if:profile_type,etudiant|nullable|string|max:50',
            'categorie'      => 'required_if:profile_type,personnel|nullable|in:per,pats',
            'matricule'      => 'required_if:profile_type,personnel|nullable|string|max:50',
        ], [
            'cycle.required_if'        => 'Veuillez indiquer votre cycle.',
            'numero_carte.required_if' => 'Le numéro de carte étudiant est requis.',
            'categorie.required_if'    => 'Veuillez indiquer si vous êtes PER ou PATS.',
            'matricule.required_if'    => 'Le matricule est requis.',
        ]);

        if ($data['profile_type'] === 'etudiant') {
            $student = Student::where('numero_carte', $data['numero_carte'])->first();
            if (!$student) {
                return back()->withInput()->withErrors([
                    'numero_carte' => "Ce numéro de carte n'a pas été trouvé dans la base StudentCenter. Vérifiez votre saisie ou contactez l'organisation.",
                ]);
            }

            session([self::SESSION_IDENTITY_KEY => [
                'type'                 => 'etudiant',
                'nom'                  => $student->nom,
                'prenom'               => $student->prenom,
                'structure_label'      => $student->structure,
                'population_category'  => $student->populationCategoryValue() ?? $data['cycle'],
                'numero_carte'         => $data['numero_carte'],
                'cin'                  => $data['cin'] ?? null,
            ]]);
        } else {
            $personnel = Personnel::where('matricule', $data['matricule'])->first();
            if (!$personnel) {
                return back()->withInput()->withErrors([
                    'matricule' => "Ce matricule n'a pas été trouvé. Vérifiez votre saisie ou contactez l'organisation.",
                ]);
            }

            session([self::SESSION_IDENTITY_KEY => [
                'type'                 => 'personnel',
                'nom'                  => $personnel->nom,
                'prenom'               => $personnel->prenom,
                'structure_label'      => $personnel->structure,
                'population_category'  => $personnel->categorie,
                'matricule'            => $data['matricule'],
            ]]);
        }

        return redirect()->route('public.project-submission.details', $eventSlug);
    }

    // ── Étape 2 : informations essentielles + choix du template ────────────

    public function details(string $eventSlug)
    {
        $event = $this->event($eventSlug);
        if (!$event->isSubmissionOpen()) {
            return redirect()->route('public.project-submission.identify', $eventSlug)
                ->with('error', $this->submissionBlockMessage($event));
        }
        $identity = session(self::SESSION_IDENTITY_KEY);
        if (!$identity) {
            return redirect()->route('public.project-submission.identify', $eventSlug);
        }

        $structures = Structure::orderBy('name')->get();

        return view('public.project-submission-details', compact('event', 'identity', 'structures'));
    }

    public function storeDetails(Request $request, string $eventSlug)
    {
        $event = $this->event($eventSlug);
        if (!$event->isSubmissionOpen()) {
            return redirect()->route('public.project-submission.identify', $eventSlug)
                ->with('error', $this->submissionBlockMessage($event));
        }
        $identity = session(self::SESSION_IDENTITY_KEY);
        if (!$identity) {
            return redirect()->route('public.project-submission.identify', $eventSlug);
        }

        $data = $request->validate([
            'email'                => 'required|email|max:255',
            'phone'                => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'structure_id'         => 'required|exists:structures,id',
            'title'                => 'required|string|max:500',
            'submission_template'  => 'required|in:standard,approfondi',
        ], [
            'phone.regex' => 'Le numéro doit commencer par 70, 71, 75, 76, 77 ou 78 et contenir exactement 9 chiffres.',
        ]);

        $structure = Structure::findOrFail($data['structure_id']);
        if (!$structure->canAddProjects(1)) {
            return back()->withInput()->withErrors([
                'structure_id' => 'Cette structure a atteint son quota maximum de projets pour cet événement.',
            ]);
        }

        // Un porteur déjà identifié (numéro de carte / matricule déjà vu) retrouve son
        // compte technique existant au lieu d'en dupliquer un nouveau à chaque dépôt.
        $user = null;
        if (!empty($identity['numero_carte'])) {
            $user = User::where('numero_carte', $identity['numero_carte'])->first();
        } elseif (!empty($identity['matricule'])) {
            $user = User::where('matricule', $identity['matricule'])->first();
        }

        if ($user) {
            if ($user->email !== $data['email'] && User::where('email', $data['email'])->where('id', '!=', $user->id)->exists()) {
                return back()->withInput()->withErrors(['email' => 'Cet email est déjà utilisé par un autre dossier.']);
            }
            $user->update([
                'name'         => trim($identity['prenom'] . ' ' . $identity['nom']),
                'email'        => $data['email'],
                'phone'        => $data['phone'] ?? $user->phone,
                'structure_id' => $structure->id,
            ]);
        } else {
            if (User::where('email', $data['email'])->exists()) {
                return back()->withInput()->withErrors([
                    'email' => 'Cet email est déjà utilisé par un autre dossier. Si vous avez déjà déposé un projet, ré-identifiez-vous avec le même numéro de carte / matricule.',
                ]);
            }
            $user = User::create([
                'name'                 => trim($identity['prenom'] . ' ' . $identity['nom']),
                'email'                => $data['email'],
                'phone'                => $data['phone'] ?? null,
                'password'             => bcrypt(Str::random(32)),
                'role'                 => 'porteur_projet',
                'structure_id'         => $structure->id,
                'is_active'            => true,
                'numero_carte'         => $identity['numero_carte'] ?? null,
                'matricule'            => $identity['matricule'] ?? null,
                'cin'                  => $identity['cin'] ?? null,
                'population_category'  => $identity['population_category'] ?? null,
            ]);
        }

        $assignment = ProjectAssignment::create([
            'porteur_id'   => $user->id,
            'structure_id' => $structure->id,
            'title'        => trim($data['title']),
            'status'       => 'pending',
            'public_token' => (string) Str::uuid(),
        ]);

        session()->forget(self::SESSION_IDENTITY_KEY);

        return redirect()->route('public.project-submission.fill', [
            'eventSlug'  => $eventSlug,
            'assignment' => $assignment->id,
            'token'      => $assignment->public_token,
            'template'   => $data['submission_template'],
        ]);
    }

    // ── Étape 3 : remplissage du formulaire (réutilise le moteur existant) ─

    private function authorizeToken(ProjectAssignment $assignment, string $token): void
    {
        abort_unless($assignment->public_token && hash_equals($assignment->public_token, $token), 403);
    }

    public function fill(Request $request, string $eventSlug, ProjectAssignment $assignment, string $token)
    {
        $event = $this->event($eventSlug);
        $this->authorizeToken($assignment, $token);

        $project = $assignment->project;
        if ($project && $project->isSubmitted()) {
            return redirect()->route('public.project-submission.show', [$eventSlug, $assignment, $token]);
        }

        if (!$event->isSubmissionOpen()) {
            return redirect()->route('public.project-submission.identify', $eventSlug)
                ->with('error', $this->submissionBlockMessage($event));
        }

        $template = $project?->submission_template ?? ($request->query('template') === 'approfondi' ? 'approfondi' : 'standard');

        if ($project) {
            $project->load('collaborators', 'coPorteurs', 'approfondiDetails');
        }

        return view('public.project-submission-form', [
            'event'              => $event,
            'assignment'         => $assignment,
            'token'              => $token,
            'project'            => $project,
            'template'           => $template,
            'formOptions'        => $this->loadFormOptions(),
            'collaboratorRoles'  => FormOption::forGroup('collaborator_role'),
        ]);
    }

    public function save(Request $request, string $eventSlug, ProjectAssignment $assignment, string $token)
    {
        $event = $this->event($eventSlug);
        $this->authorizeToken($assignment, $token);

        $project = $assignment->project;
        if ($project && $project->isSubmitted()) {
            return back()->with('error', 'Ce projet a déjà été soumis et ne peut plus être modifié.');
        }

        if (!$event->isSubmissionOpen()) {
            return back()->with('error', $this->submissionBlockMessage($event));
        }

        $template = $project?->submission_template
            ?? ($request->input('submission_template') === 'approfondi' ? 'approfondi' : 'standard');

        $data = $template === 'approfondi' ? $this->validateApprofondiForm($request) : $this->validateForm($request);

        if ($project) {
            $project->update($data);
        } else {
            $data['assignment_id']       = $assignment->id;
            $data['porteur_id']          = $assignment->porteur_id;
            $data['structure_id']        = $assignment->structure_id;
            $data['status']              = 'draft';
            $data['submission_template'] = $template;
            $project = Project::create($data);
        }

        $this->saveCollaborators($project, $request->input('collaborateurs', []), 'collaborateur');
        if ($template === 'approfondi') {
            $this->saveCollaborators($project, $request->input('co_porteurs', []), 'co_porteur');
            $this->saveApprofondiDetails($project, $request);
        }

        return redirect()->route('public.project-submission.fill', [$eventSlug, $assignment, $token])
            ->with('success', 'Brouillon enregistré. Conservez ce lien pour revenir compléter votre dossier.');
    }

    public function submit(string $eventSlug, ProjectAssignment $assignment, string $token)
    {
        $event = $this->event($eventSlug);
        $this->authorizeToken($assignment, $token);

        $project = $assignment->project;
        abort_if(!$project, 404);
        if ($project->isSubmitted()) {
            return back()->with('error', 'Ce projet est déjà soumis.');
        }

        if (!$event->isSubmissionOpen()) {
            return back()->with('error', $this->submissionBlockMessage($event));
        }

        $project->update(['status' => 'submitted']);
        $assignment->update(['status' => 'submitted']);

        try {
            $project->load('collaborators', 'assignment', 'structure', 'porteur');

            $primaryEmail = !empty($project->email_professionnel)
                ? $project->email_professionnel
                : $project->porteur->email;

            $mailer = Mail::to($primaryEmail);

            $collabEmails = $project->collaborators
                ->filter(fn ($c) => !empty($c->email))
                ->map(fn ($c) => $c->email)
                ->values()
                ->toArray();

            if (!empty($collabEmails)) {
                $mailer = $mailer->cc($collabEmails);
            }

            $mailer->send(new SubmissionConfirmationMail($project->porteur, $project));
        } catch (\Exception) {}

        return redirect()->route('public.project-submission.show', [$eventSlug, $assignment, $token])
            ->with('success', 'Dossier soumis avec succès. Un email de confirmation vous a été envoyé.');
    }

    public function show(string $eventSlug, ProjectAssignment $assignment, string $token)
    {
        $this->event($eventSlug);
        $this->authorizeToken($assignment, $token);

        $project = $assignment->project;
        abort_if(!$project, 404);
        $project->load('collaborators', 'coPorteurs', 'approfondiDetails', 'assignment', 'structure');

        return view('public.project-submission-show', compact('project', 'eventSlug', 'assignment', 'token'));
    }
}
