<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $event = EventConfig::active();
        $query = Registration::query();

        if ($event) {
            $query->where('event_config_id', $event->id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            $query->where('type_participant', $type);
        }

        if ($request->get('presence') === '1') {
            $query->where('presence_confirmee', true);
        } elseif ($request->get('presence') === '0') {
            $query->where('presence_confirmee', false);
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();

        return view('secretaire.inscriptions.index', compact('registrations', 'event'));
    }

    public function show(Registration $registration)
    {
        return view('secretaire.inscriptions.show', compact('registration'));
    }

    public function togglePresence(Registration $registration)
    {
        $registration->update(['presence_confirmee' => !$registration->presence_confirmee]);
        return back()->with('success', 'Présence mise à jour.');
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();
        return redirect()->route('secretaire.inscriptions.index')->with('success', 'Inscription supprimée.');
    }
}
