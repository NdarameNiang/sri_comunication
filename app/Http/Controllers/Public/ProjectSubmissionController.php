<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\PorteurCredentialsMail;
use App\Models\EventConfig;
use App\Models\FormOption;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectSubmissionController extends Controller
{
    public function show(string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();
        abort_unless($event->allowsPublicSubmission(), 404);

        $structures = Structure::orderBy('name')->get();
        $populationCategories = FormOption::forGroup('population_category');

        return view('public.project-submission', compact('event', 'structures', 'populationCategories'));
    }

    public function store(Request $request, string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();
        abort_unless($event->allowsPublicSubmission(), 404);

        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|max:255',
            'phone'                => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'structure_id'         => 'required|exists:structures,id',
            'population_category'  => 'required|in:per,pats,etudiant_licence,etudiant_master,etudiant_doctorat',
            'numero_carte'         => ['nullable', 'string', 'max:50', 'required_if:population_category,etudiant_licence,etudiant_master,etudiant_doctorat'],
            'title'                => 'required|string|max:500',
            'submission_template'  => 'required|in:standard,approfondi',
        ], [
            'phone.regex'               => 'Le numéro doit commencer par 70, 71, 75, 76, 77 ou 78 et contenir exactement 9 chiffres.',
            'numero_carte.required_if'  => 'Le numéro de carte étudiant est requis pour cette catégorie.',
        ]);

        if (User::where('email', $data['email'])->exists()) {
            return back()->withInput()->withErrors([
                'email' => 'Un compte existe déjà avec cet email. Connectez-vous pour soumettre votre projet depuis votre tableau de bord.',
            ]);
        }

        // Vérification StudentCenter : bloquante pour une catégorie étudiante déclarée, car
        // c'est cette vérification qui justifie la création d'un compte porteur sans validation
        // manuelle par un administrateur.
        if (!empty($data['numero_carte'])) {
            $student = Student::where('numero_carte', $data['numero_carte'])->first();
            if (!$student) {
                return back()->withInput()->withErrors([
                    'numero_carte' => "Ce numéro de carte n'a pas été trouvé dans la base StudentCenter. Vérifiez votre saisie ou contactez l'organisation.",
                ]);
            }
            if ($student->populationCategoryValue()) {
                $data['population_category'] = $student->populationCategoryValue();
            }
        }

        $structure = Structure::findOrFail($data['structure_id']);
        if (!$structure->canAddProjects(1)) {
            return back()->withInput()->withErrors([
                'structure_id' => "Cette structure a atteint son quota maximum de projets pour cet événement.",
            ]);
        }

        $plainPassword = Str::password(10, symbols: false);

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'] ?? null,
            'password'     => bcrypt($plainPassword),
            'role'         => 'porteur_projet',
            'structure_id' => $structure->id,
            'is_active'    => true,
        ]);

        $assignment = ProjectAssignment::create([
            'porteur_id'   => $user->id,
            'structure_id' => $structure->id,
            'title'        => trim($data['title']),
            'status'       => 'pending',
        ]);

        try {
            Mail::to($user->email)->send(new PorteurCredentialsMail($user, $plainPassword));
        } catch (\Exception) {
            // La connexion immédiate ci-dessous prend le relais si l'email échoue ;
            // l'utilisateur peut toujours régénérer ses identifiants plus tard depuis /login.
        }

        Auth::login($user);

        return redirect()->route('porteur.projects.create', [
            'assignment' => $assignment,
            'template'   => $data['submission_template'],
        ])->with('success', 'Votre espace porteur a été créé. Vos identifiants vous ont été envoyés par email — vous pouvez maintenant compléter votre soumission.');
    }
}
