@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', 'Organisateur')
@section('page-subtitle', 'Pilotage de l\'appel à communication SRI 2026')

@section('content')
<div class="space-y-6">

    {{-- ── Stats ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php
        $cards = [
            ['label' => 'Porteurs de projet',  'value' => $stats['porteurs'],     'bar' => 'bg-emerald-500'],
            ['label' => 'Observateurs',        'value' => $stats['point_focaux'], 'bar' => 'bg-amber-500'],
            ['label' => 'Comité Scientifique',  'value' => $stats['comite'],       'bar' => 'bg-purple-500'],
            ['label' => 'Projets assignés',     'value' => $stats['assignments'],  'bar' => 'bg-blue-500'],
            ['label' => 'Projets soumis',       'value' => $stats['submitted'],    'bar' => 'bg-cyan-500'],
        ];
        @endphp
        @foreach($cards as $c)
        <div class="card p-5">
            <p class="text-2xl font-bold text-gray-900">{{ $c['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $c['label'] }}</p>
            <div class="mt-3 h-1 rounded-full {{ $c['bar'] }} opacity-30"></div>
        </div>
        @endforeach
    </div>

    {{-- ── Actions rapides ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
            ['route' => 'direction.porteurs.create',    'label' => 'Nouveau porteur',    'sub' => 'Créer un compte porteur',  'color' => 'emerald'],
            ['route' => 'direction.point-focaux.create','label' => 'Nouvel Observateur', 'sub' => 'Affecter un observateur', 'color' => 'amber'],
            ['route' => 'direction.comite.create',      'label' => 'Membre comité',       'sub' => 'Ajouter un évaluateur',   'color' => 'purple'],
        ] as $action)
        <a href="{{ route($action['route']) }}"
           class="card p-5 hover:shadow-md transition-shadow group flex items-center gap-4">
            <div class="w-12 h-12 bg-{{ $action['color'] }}-100 rounded-xl flex items-center justify-center group-hover:bg-{{ $action['color'] }}-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-{{ $action['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">{{ $action['label'] }}</p>
                <p class="text-xs text-gray-500">{{ $action['sub'] }}</p>
            </div>
        </a>
        @endforeach
    </div>

    {{-- ── Avancement par structure ─────────────────────────────────── --}}
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3">
            <h3 class="section-title text-base shrink-0">Avancement par structure</h3>

            {{-- Barre de recherche --}}
            <form method="GET" action="{{ route('direction.dashboard') }}" class="flex items-center gap-2 w-full sm:w-auto sm:ml-auto">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex-1 sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Rechercher une structure…"
                           class="form-input pl-9 py-2 text-sm">
                </div>
                <button type="submit" class="btn-primary py-2">Filtrer</button>
                @if($search)
                    <a href="{{ route('direction.dashboard', ['tab' => $tab]) }}" class="btn-secondary py-2 text-xs">✕</a>
                @endif
            </form>
        </div>

        {{-- Onglets --}}
        @php
        $tabs = [
            'tous'       => ['label' => 'Toutes',      'color' => 'gray'],
            'non_entame' => ['label' => 'Non entamées','color' => 'red'],
            'en_cours'   => ['label' => 'En cours',    'color' => 'amber'],
            'complete'   => ['label' => 'Complétées',  'color' => 'emerald'],
        ];
        @endphp
        <div class="flex gap-1 px-4 pt-3 border-b border-gray-100">
            @foreach($tabs as $key => $meta)
            @php
                $isActive = $tab === $key;
                $count    = $tabCounts[$key];
            @endphp
            <a href="{{ route('direction.dashboard', ['tab' => $key, 'search' => $search]) }}"
               class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition-all
                   {{ $isActive
                       ? 'border-blue-600 text-blue-700 bg-blue-50/50'
                       : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ $meta['label'] }}
                <span class="inline-flex items-center justify-center text-xs rounded-full px-1.5 py-0.5 font-semibold
                    {{ $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $count }}
                </span>
            </a>
            @endforeach
        </div>

        {{-- Tableau --}}
        <div class="table-container rounded-none border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Structure</th>
                        <th>Sigle</th>
                        <th class="text-center">Projets assignés</th>
                        <th class="text-center">Projets soumis</th>
                        <th>Statut</th>
                        <th>Capacité de projets</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($structureStats as $s)
                    @php
                        $total     = $s->project_assignments_count;
                        $submitted = $s->submitted_count;
                        $pct       = $total > 0 ? round($total / 5 * 100) : 0;

                        if ($total === 0) {
                            $statusBadge = '<span class="badge-red">Non entamée</span>';
                            $barColor    = 'bg-gray-200';
                        } elseif ($submitted >= $total) {
                            $statusBadge = '<span class="badge-green">Complétée</span>';
                            $barColor    = 'bg-emerald-500';
                        } else {
                            $statusBadge = '<span class="badge-yellow">En cours</span>';
                            $barColor    = 'bg-amber-400';
                        }
                    @endphp
                    <tr>
                        <td class="font-medium text-gray-900 max-w-xs">
                            <span class="line-clamp-1" title="{{ $s->name }}">{{ $s->name }}</span>
                        </td>
                        <td><span class="badge-blue">{{ $s->acronym }}</span></td>
                        <td class="text-center">
                            <span class="font-semibold text-gray-900">{{ $total }}</span>
                            <span class="text-gray-400 text-xs">/ 5</span>
                        </td>
                        <td class="text-center">
                            <span class="{{ $submitted > 0 ? 'badge-green' : 'badge-gray' }}">{{ $submitted }}</span>
                        </td>
                        <td>{!! $statusBadge !!}</td>
                        <td class="w-36">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $barColor }} transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 text-right">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Aucune structure trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($structureStats->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $structureStats->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
