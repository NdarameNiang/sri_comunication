<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\FormOption;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistrationController extends Controller
{
    public function show(string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();
        $participantTypes = FormOption::forGroup('participant_type');
        return view('public.registration', compact('event', 'participantTypes'));
    }

    public function store(Request $request, string $eventSlug)
    {
        $event = EventConfig::where('event_slug', $eventSlug)->where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'nom'              => 'required|string|max:255',
            'prenom'           => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|same:email_confirmation',
            'email_confirmation' => 'nullable|email|max:255',
            'telephone'        => ['nullable', 'regex:/^(70|71|75|76|77|78)\d{7}$/'],
            'institution'      => 'nullable|string|max:255',
            'fonction'         => 'nullable|string|max:255',
            'type_participant' => 'nullable|string|max:100',
        ], [
            'email.same' => 'Les deux adresses email ne correspondent pas.',
        ]);

        unset($data['email_confirmation']);

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
