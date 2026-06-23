@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'Pilotage de l\'appel à communication SRI 2026')

@section('content')
<div class="space-y-6">

    {{-- ── Stat cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
            ['label' => 'Porteurs de projet',  'value' => $stats['porteurs'],     'bg' => 'bg-emerald-100', 'fg' => 'text-emerald-600', 'icon' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z'],
            ['label' => 'Observateurs',        'value' => $stats['point_focaux'], 'bg' => 'bg-amber-100',   'fg' => 'text-amber-600',   'icon' => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => 'Comité Scientifique', 'value' => $stats['comite'],       'bg' => 'bg-purple-100',  'fg' => 'text-purple-600',  'icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5'],
            ['label' => 'Projets assignés',    'value' => $stats['assignments'],  'bg' => 'bg-blue-100',    'fg' => 'text-blue-600',    'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
            ['label' => 'Projets soumis',      'value' => $stats['submitted'],    'bg' => 'bg-cyan-100',    'fg' => 'text-cyan-600',    'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $c)
        <div class="stat-card flex items-center gap-3">
            <div class="stat-icon {{ $c['bg'] }} shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $c['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $c['value'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $c['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Actions rapides ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([
            ['route' => 'direction.porteurs.create',    'label' => 'Nouveau porteur',    'sub' => 'Créer un compte porteur', 'bg' => 'bg-emerald-100', 'fg' => 'text-emerald-600', 'hbg' => 'hover:bg-emerald-200'],
            ['route' => 'direction.point-focaux.create','label' => 'Nouvel Observateur', 'sub' => 'Affecter un observateur', 'bg' => 'bg-amber-100',   'fg' => 'text-amber-600',   'hbg' => 'hover:bg-amber-200'],
            ['route' => 'direction.comite.create',      'label' => 'Membre comité',       'sub' => 'Ajouter un évaluateur',  'bg' => 'bg-purple-100',  'fg' => 'text-purple-600',  'hbg' => 'hover:bg-purple-200'],
        ] as $action)
        <a href="{{ route($action['route']) }}"
           class="card p-5 hover:shadow-md transition-all group flex items-center gap-4">
            <div class="w-12 h-12 {{ $action['bg'] }} {{ $action['hbg'] }} rounded-xl flex items-center justify-center transition-colors shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ $action['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">{{ $action['label'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $action['sub'] }}</p>
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
