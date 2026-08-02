<?php

namespace App\Http\Controllers\DirectionRecherche;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $event = EventConfig::active();

        $stats = [
            'porteurs'     => User::where('role', 'porteur_projet')->count(),
            'point_focaux' => User::where('role', 'point_focal')->count(),
            'comite'       => User::where('role', 'comite_scientifique')->count(),
            'secretaires'  => User::where('role', 'secretaire')->count(),
            'assignments'  => ProjectAssignment::when($event, fn($q) => $q->where('event_config_id', $event->id))->count(),
            'submitted'    => Project::where('status', 'submitted')->forEvent($event)->count(),
        ];

        $tab    = $request->input('tab', 'tous');
        $search = trim($request->input('search', ''));

        // Toutes les structures avec compteurs, limités à l'événement actif
        $all = Structure::withCount([
            'projectAssignments' => fn($q) => $q->when($event, fn($qq) => $qq->where('event_config_id', $event->id)),
            'projectAssignments as submitted_count' => fn($q) => $q->where('status', 'submitted')
                ->when($event, fn($qq) => $qq->where('event_config_id', $event->id)),
        ])->orderBy('name')->get();

        // Filtre recherche
        if ($search !== '') {
            $needle = mb_strtolower($search);
            $all = $all->filter(fn($s) =>
                str_contains(mb_strtolower($s->name), $needle) ||
                str_contains(mb_strtolower($s->acronym ?? ''), $needle)
            );
        }

        // Compteurs par onglet (avant filtre onglet)
        $tabCounts = [
            'tous'       => $all->count(),
            'non_entame' => $all->filter(fn($s) => $s->project_assignments_count === 0)->count(),
            'en_cours'   => $all->filter(fn($s) => $s->project_assignments_count > 0 && $s->submitted_count < $s->project_assignments_count)->count(),
            'complete'   => $all->filter(fn($s) => $s->project_assignments_count > 0 && $s->submitted_count >= $s->project_assignments_count)->count(),
        ];

        // Filtre onglet
        $filtered = match ($tab) {
            'non_entame' => $all->filter(fn($s) => $s->project_assignments_count === 0),
            'en_cours'   => $all->filter(fn($s) => $s->project_assignments_count > 0 && $s->submitted_count < $s->project_assignments_count),
            'complete'   => $all->filter(fn($s) => $s->project_assignments_count > 0 && $s->submitted_count >= $s->project_assignments_count),
            default      => $all,
        };

        // Pagination manuelle
        $perPage = 15;
        $page    = (int) $request->input('page', 1);

        $structureStats = new LengthAwarePaginator(
            $filtered->slice(($page - 1) * $perPage, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('direction.dashboard', compact('stats', 'structureStats', 'tabCounts', 'tab', 'search'));
    }
}
