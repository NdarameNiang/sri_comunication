<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request)
    {
        $event = EventConfig::active();
        $query = Questionnaire::query();

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

        if ($note = $request->get('note_min')) {
            $query->where('note_globale', '>=', (int) $note);
        }

        $questionnaires = $query->latest()->paginate(25)->withQueryString();

        $stats = $event ? [
            'total'        => Questionnaire::where('event_config_id', $event->id)->count(),
            'recommandent' => Questionnaire::where('event_config_id', $event->id)->where('recommanderait', true)->count(),
        ] : null;

        $moyennes = $event ? [
            'organisation' => round(Questionnaire::where('event_config_id', $event->id)->avg('note_organisation'), 1),
            'contenu'      => round(Questionnaire::where('event_config_id', $event->id)->avg('note_contenu'), 1),
            'logistique'   => round(Questionnaire::where('event_config_id', $event->id)->avg('note_logistique'), 1),
            'globale'      => round(Questionnaire::where('event_config_id', $event->id)->avg('note_globale'), 1),
        ] : null;

        return view('secretaire.questionnaires.index', compact('questionnaires', 'event', 'moyennes', 'stats'));
    }

    public function show(Questionnaire $questionnaire)
    {
        return view('secretaire.questionnaires.show', compact('questionnaire'));
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->delete();
        return redirect()->route('secretaire.questionnaires.index')->with('success', 'Questionnaire supprimé.');
    }

    public function export(Request $request)
    {
        $event = EventConfig::active();
        $questionnaires = Questionnaire::query()
            ->when($event, fn($q) => $q->where('event_config_id', $event->id))
            ->oldest()
            ->get();

        $filename = 'questionnaires-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($questionnaires) {
            $fp = fopen('php://output', 'w');
            fputs($fp, "\xEF\xBB\xBF");
            fputcsv($fp, [
                'Nom', 'Prénom', 'Email', 'Institution',
                'Note Organisation', 'Note Contenu', 'Note Logistique', 'Note Globale',
                'Recommanderait', 'Points positifs', 'Points à améliorer', 'Suggestions',
                'Date soumission',
            ], ';');
            foreach ($questionnaires as $q) {
                fputcsv($fp, [
                    $q->nom ?? '',
                    $q->prenom ?? '',
                    $q->email ?? '',
                    $q->institution ?? '',
                    $q->note_organisation ?? '',
                    $q->note_contenu ?? '',
                    $q->note_logistique ?? '',
                    $q->note_globale ?? '',
                    $q->recommanderait ? 'Oui' : 'Non',
                    $q->points_positifs ?? '',
                    $q->points_amelioration ?? '',
                    $q->suggestions ?? '',
                    $q->created_at->format('d/m/Y H:i'),
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

        $handle = fopen($request->file('fichier')->getRealPath(), 'r');

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        fgetcsv($handle, 0, ';'); // skip header

        $count = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (empty($row[0]) && empty($row[1])) continue;

            Questionnaire::create([
                'event_config_id'    => $event->id,
                'nom'                => trim($row[0] ?? ''),
                'prenom'             => trim($row[1] ?? ''),
                'email'              => !empty($row[2]) ? trim($row[2]) : null,
                'institution'        => !empty($row[3]) ? trim($row[3]) : null,
                'note_organisation'  => is_numeric($row[4] ?? '') ? (int)$row[4] : null,
                'note_contenu'       => is_numeric($row[5] ?? '') ? (int)$row[5] : null,
                'note_logistique'    => is_numeric($row[6] ?? '') ? (int)$row[6] : null,
                'note_globale'       => is_numeric($row[7] ?? '') ? (int)$row[7] : null,
                'recommanderait'     => isset($row[8]) && strtolower(trim($row[8])) === 'oui',
                'points_positifs'    => !empty($row[9])  ? trim($row[9])  : null,
                'points_amelioration'=> !empty($row[10]) ? trim($row[10]) : null,
                'suggestions'        => !empty($row[11]) ? trim($row[11]) : null,
            ]);
            $count++;
        }
        fclose($handle);

        return back()->with('success', "{$count} réponse(s) importée(s) avec succès.");
    }
}
