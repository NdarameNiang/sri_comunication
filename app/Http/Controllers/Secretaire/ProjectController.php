<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['porteur', 'structure', 'assignment', 'collaborators'])
            ->where('status', 'submitted');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('responsable_nom', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhereHas('structure', fn($s) => $s->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('porteur', fn($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        if ($domain = $request->get('domain')) {
            $query->where('scientific_domain', $domain);
        }

        if ($request->get('selected') === '1') {
            $query->where('selected', true);
        } elseif ($request->get('selected') === '0') {
            $query->where('selected', false);
        }

        $projects = $query->latest()->paginate(20)->withQueryString();

        $domains = Project::where('status', 'submitted')
            ->whereNotNull('scientific_domain')
            ->distinct()->pluck('scientific_domain')->sort()->values();

        return view('secretaire.projets.index', compact('projects', 'domains'));
    }

    public function show(Project $project)
    {
        $project->load(['porteur', 'structure', 'assignment', 'collaborators']);
        return view('secretaire.projets.show', compact('project'));
    }

    public function export()
    {
        $projects = Project::with(['porteur', 'structure', 'collaborators'])
            ->where('status', 'submitted')
            ->latest()->get();

        $filename = 'projets-soumis-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($projects) {
            $fp = fopen('php://output', 'w');
            fputs($fp, "\xEF\xBB\xBF");
            fputcsv($fp, [
                'ID', 'Porteur', 'Email porteur', 'Structure',
                'Responsable', 'Email contact', 'Email personnel', 'Téléphone',
                'Domaine scientifique', 'Types de projet', 'Résumé',
                'Problématique', 'Solution', 'Résultats',
                'Niveau de maturité', 'Types protection', 'Types valorisation',
                'Types impact', 'Formats présentation', 'Besoins logistiques',
                'Sélectionné', 'Date soumission',
            ], ';');

            foreach ($projects as $p) {
                fputcsv($fp, [
                    $p->id,
                    $p->porteur?->name ?? '',
                    $p->porteur?->email ?? '',
                    $p->structure?->name ?? '',
                    $p->responsable_nom,
                    $p->contact_email,
                    $p->email_professionnel ?? '',
                    $p->contact_phone ?? '',
                    $p->scientific_domain,
                    implode(', ', array_map(fn($t) => Project::projectTypeLabels()[$t] ?? $t, (array)($p->project_types ?? []))),
                    strip_tags($p->summary),
                    strip_tags($p->problematic),
                    strip_tags($p->solution),
                    strip_tags($p->results ?? ''),
                    $p->maturity_level ? (Project::maturityLabels()[$p->maturity_level] ?? $p->maturity_level) : '',
                    implode(', ', array_map(fn($t) => Project::protectionLabels()[$t] ?? $t, (array)($p->protection_types ?? []))),
                    implode(', ', array_map(fn($t) => Project::valorisationLabels()[$t] ?? $t, (array)($p->valorisation_types ?? []))),
                    implode(', ', array_map(fn($t) => Project::impactLabels()[$t] ?? $t, (array)($p->impact_types ?? []))),
                    implode(', ', array_map(fn($t) => Project::presentationLabels()[$t] ?? $t, (array)($p->presentation_formats ?? []))),
                    strip_tags($p->logistic_needs ?? ''),
                    $p->selected ? 'Oui' : 'Non',
                    $p->updated_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($fp);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
