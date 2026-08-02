<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\FormOption;
use App\Models\Personnel;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistrationController extends Controller
{
    public function show(string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();
        $participantTypes = FormOption::forGroup('participant_type');
        $populationCategories = $event->isAudienceRestricted()
            ? $event->audienceCategories()->orderBy('sort_order')->get()
            : FormOption::forGroup('population_category');
        $inscriptionClosed = !$event->isInscriptionOpen();
        $requireVerification = $event->requiresIdentityVerification();
        $profileTypes = $event->enabledProfileTypes();
        return view('public.registration', compact('event', 'participantTypes', 'populationCategories', 'inscriptionClosed', 'requireVerification', 'profileTypes'));
    }

    /**
     * Recherche AJAX par numéro de carte (étudiant) ou matricule (personnel), utilisée quand
     * l'événement exige une vérification d'identité : le visiteur saisit son numéro en premier,
     * et le formulaire se pré-remplit (nom, prénom, catégorie) sans qu'il ait à ressaisir des
     * informations déjà connues en base.
     */
    public function lookup(Request $request, string $eventSlug)
    {
        $numero = trim((string) $request->query('numero_carte'));
        if ($numero === '') {
            return response()->json(['found' => false]);
        }

        $student = Student::where('numero_carte', $numero)->first();
        if ($student) {
            return response()->json([
                'found'               => true,
                'type'                => 'etudiant',
                'nom'                 => $student->nom,
                'prenom'              => $student->prenom,
                'population_category' => $student->populationCategoryValue(),
                'institution'         => $student->structure,
            ]);
        }

        $personnel = Personnel::where('matricule', $numero)->first();
        if ($personnel) {
            return response()->json([
                'found'               => true,
                'type'                => 'personnel',
                'nom'                 => $personnel->nom,
                'prenom'              => $personnel->prenom,
                'population_category' => $personnel->populationCategoryValue(),
                'institution'         => $personnel->structure,
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function store(Request $request, string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();

        if (!$event->isInscriptionOpen()) {
            return back()->with('error', 'Les inscriptions sont actuellement fermées.');
        }

        $allowedCategories = $event->isAudienceRestricted()
            ? $event->allowedAudienceValues()
            : FormOption::forGroup('population_category')->pluck('value')->all();

        $data = $request->validate([
            'nom'              => 'required|string|max:255',
            'prenom'           => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|same:email_confirmation',
            'email_confirmation' => 'nullable|email|max:255',
            'telephone'        => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'institution'      => 'nullable|string|max:255',
            'fonction'         => 'nullable|string|max:255',
            'type_participant' => 'nullable|string|max:100',
            'population_category' => ['nullable', Rule::in($allowedCategories)],
            'numero_carte'     => ['nullable', 'string', 'max:50', 'required_if:population_category,etudiant_licence,etudiant_master,etudiant_doctorat'],
        ], [
            'email.same' => 'Les deux adresses email ne correspondent pas.',
            'population_category.in' => "Cette catégorie n'est pas ouverte à l'inscription pour cet événement.",
            'numero_carte.required_if' => 'Le numéro de carte étudiant est requis pour cette catégorie.',
        ]);

        unset($data['email_confirmation']);

        // Vérification du numéro de carte contre la base StudentCenter/Personnel : si
        // l'événement l'exige, le numéro doit correspondre à une fiche connue, sinon
        // l'inscription est refusée. Sinon (événement en mode libre), la correspondance est
        // seulement utilisée pour réaligner la catégorie quand elle existe, sans jamais bloquer.
        // Si l'admin n'a activé aucun profil, le champ n'est même pas affiché côté formulaire.
        if (!empty($event->enabledProfileTypes()) && !empty($data['numero_carte'])) {
            $student = Student::where('numero_carte', $data['numero_carte'])->first();
            $personnel = $student ? null : Personnel::where('matricule', $data['numero_carte'])->first();
            $match = $student ?? $personnel;

            if ($event->requiresIdentityVerification() && !$match) {
                return back()->withInput()->withErrors([
                    'numero_carte' => "Ce numéro n'a pas été trouvé dans la base StudentCenter/Personnel. Vérifiez votre saisie ou contactez l'organisation.",
                ]);
            }

            if ($match && $match->populationCategoryValue()) {
                $data['population_category'] = $match->populationCategoryValue();
            }
        }

        $token = Str::uuid()->toString();
        $data['event_config_id']  = $event->id;
        $data['qr_code']          = $token;
        $data['presence_confirmee'] = false;

        $registration = Registration::create($data);

        return redirect()->route('public.registration.confirmation', [
            'eventSlug' => $eventSlug,
            'token'     => $token,
        ]);
    }

    public function confirmation(string $eventSlug, string $token)
    {
        $event        = EventConfig::where('event_slug', $eventSlug)->firstOrFail();
        $registration = Registration::where('qr_code', $token)->firstOrFail();

        $confirmationUrl = route('public.registration.confirmation', [
            'eventSlug' => $eventSlug,
            'token'     => $token,
        ]);

        $qrSvg = QrCode::size(200)->generate($confirmationUrl);

        return view('public.registration-confirmation', compact('event', 'registration', 'qrSvg'));
    }
}
