<?php

namespace App\Http\Controllers\ComiteScientifique;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Structure;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function export(Request $request)
    {
        $structureId = $request->get('structure');

        $query = Project::with(['porteur', 'structure', 'assignment', 'collaborators'])
            ->where('status', 'submitted');

        if ($structureId) {
            $query->where('structure_id', $structureId);
        }

        $projects = $query->orderBy('structure_id')->orderBy('id')->get();

        $structure = $structureId ? Structure::find($structureId) : null;
        $filename  = 'appels-communication-'
            . ($structure ? \Illuminate\Support\Str::slug($structure->acronym ?? $structure->name) : 'tous')
            . '-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($projects) {
            $fp = fopen('php://output', 'w');
            fputs($fp, "\xEF\xBB\xBF");
            fputcsv($fp, [
                'ID', 'Structure', 'Porteur', 'Email porteur',
                'Responsable', 'Email contact', 'Téléphone',
                'Domaine scientifique', 'Types de projet',
                'Résumé', 'Problématique', 'Solution', 'Résultats',
                'Niveau de maturité',
                'Protection IP', 'Valorisation', 'Impacts', 'Présentation',
                'Besoins logistiques',
                'Sélectionné', 'Email envoyé le',
            ], ';');

            foreach ($projects as $p) {
                fputcsv($fp, [
                    $p->id,
                    $p->structure?->acronym ?? $p->structure?->name ?? '',
                    $p->porteur?->name ?? '',
                    $p->porteur?->email ?? '',
                    $p->responsable_nom,
                    $p->contact_email,
                    $p->contact_phone ?? '',
                    $p->scientific_domain,
                    implode(', ', array_map(
                        fn($t) => Project::projectTypeLabels()[$t] ?? $t,
                        (array)($p->project_types ?? [])
                    )),
                    strip_tags($p->summary),
                    strip_tags($p->problematic),
                    strip_tags($p->solution),
                    strip_tags($p->results ?? ''),
                    $p->maturity_level ? (Project::maturityLabels()[$p->maturity_level] ?? $p->maturity_level) : '',
                    implode(', ', array_map(
                        fn($t) => Project::protectionLabels()[$t] ?? $t,
                        (array)($p->protection_types ?? [])
                    )),
                    implode(', ', array_map(
                        fn($t) => Project::valorisationLabels()[$t] ?? $t,
                        (array)($p->valorisation_types ?? [])
                    )),
                    implode(', ', array_map(
                        fn($t) => Project::impactLabels()[$t] ?? $t,
                        (array)($p->impact_types ?? [])
                    )),
                    implode(', ', array_map(
                        fn($t) => Project::presentationLabels()[$t] ?? $t,
                        (array)($p->presentation_formats ?? [])
                    )),
                    strip_tags($p->logistic_needs ?? ''),
                    $p->selected ? 'Oui' : 'Non',
                    $p->email_sent_at?->format('d/m/Y H:i') ?? '',
                ], ';');
            }
            fclose($fp);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
