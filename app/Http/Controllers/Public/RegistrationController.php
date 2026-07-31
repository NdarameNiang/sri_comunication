<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\FormOption;
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
        return view('public.registration', compact('event', 'participantTypes', 'populationCategories', 'inscriptionClosed'));
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

        // Vérification du numéro de carte contre la base StudentCenter (si synchronisée) :
        // ne bloque pas l'inscription si l'étudiant est introuvable (la synchro peut être en
        // retard), mais réaligne la catégorie sur le cycle réel quand la correspondance existe.
        if (!empty($data['numero_carte'])) {
            $student = Student::where('numero_carte', $data['numero_carte'])->first();
            if ($student && $student->populationCategoryValue()) {
                $data['population_category'] = $student->populationCategoryValue();
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
