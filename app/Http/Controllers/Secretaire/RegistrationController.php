<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $types = $event
            ? Registration::where('event_config_id', $event->id)
                ->whereNotNull('type_participant')
                ->distinct()->pluck('type_participant')->sort()->values()
            : collect();

        $stats = $event ? [
            'total'    => Registration::where('event_config_id', $event->id)->count(),
            'presents' => Registration::where('event_config_id', $event->id)->where('presence_confirmee', true)->count(),
            'absents'  => Registration::where('event_config_id', $event->id)->where('presence_confirmee', false)->count(),
        ] : null;

        return view('secretaire.inscriptions.index', compact('registrations', 'event', 'types', 'stats'));
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

    public function export(Request $request)
    {
        $event = EventConfig::active();
        $registrations = Registration::query()
            ->when($event, fn($q) => $q->where('event_config_id', $event->id))
            ->latest()->get();

        $filename = 'participants-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($registrations) {
            $fp = fopen('php://output', 'w');
            fputs($fp, "\xEF\xBB\xBF"); // UTF-8 BOM pour Excel
            fputcsv($fp, [
                'Nom', 'Prénom', 'Email', 'Téléphone',
                'Institution', 'Fonction', 'Type participant',
                'Présence confirmée', 'QR Code', 'Date inscription',
            ], ';');
            foreach ($registrations as $reg) {
                fputcsv($fp, [
                    $reg->nom,
                    $reg->prenom,
                    $reg->email ?? '',
                    $reg->telephone ?? '',
                    $reg->institution ?? '',
                    $reg->fonction ?? '',
                    $reg->type_participant ?? '',
                    $reg->presence_confirmee ? 'Oui' : 'Non',
                    $reg->qr_code ?? '',
                    $reg->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($fp);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $event = EventConfig::active();
        if (!$event) {
            return back()->with('error', 'Aucun événement actif. Impossible d\'importer.');
        }

        $file   = $request->file('fichier');
        $handle = fopen($file->getRealPath(), 'r');

        // Ignore le BOM UTF-8 si présent
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Ignore la ligne d'en-tête
        fgetcsv($handle, 0, ';');

        $count = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (empty($row[0]) && empty($row[1])) {
                continue;
            }
            Registration::create([
                'event_config_id'   => $event->id,
                'nom'               => trim($row[0] ?? ''),
                'prenom'            => trim($row[1] ?? ''),
                'email'             => !empty($row[2]) ? trim($row[2]) : null,
                'telephone'         => !empty($row[3]) ? trim($row[3]) : null,
                'institution'       => !empty($row[4]) ? trim($row[4]) : null,
                'fonction'          => !empty($row[5]) ? trim($row[5]) : null,
                'type_participant'  => !empty($row[6]) ? trim($row[6]) : null,
                'presence_confirmee'=> isset($row[7]) && strtolower(trim($row[7])) === 'oui',
                'qr_code'           => Str::uuid()->toString(),
            ]);
            $count++;
        }
        fclose($handle);

        return back()->with('success', "{$count} participant(s) importé(s) avec succès.");
    }
}
