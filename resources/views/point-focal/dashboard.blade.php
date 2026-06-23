@extends('layouts.app')
@section('title', 'Tableau de bord – Observateur')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'Suivi des projets des structures que vous observez')

@section('content')
<div class="space-y-6">

    {{-- ── Stat cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total affectations', 'value' => $stats['total'],       'bg' => 'bg-blue-100',   'fg' => 'text-blue-600',   'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Non entamés',         'value' => $stats['not_started'], 'bg' => 'bg-red-100',    'fg' => 'text-red-500',    'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'],
            ['label' => 'En cours',            'value' => $stats['draft'],       'bg' => 'bg-amber-100',  'fg' => 'text-amber-600',  'icon' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125'],
            ['label' => 'Soumis',              'value' => $stats['submitted'],   'bg' => 'bg-emerald-100','fg' => 'text-emerald-600', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $c)
        <div class="stat-card flex items-center gap-4">
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

    {{-- Structures observées --}}
    <div class="alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <strong>Structures observées :</strong>
            <div class="flex flex-wrap gap-1.5 mt-1">
                @foreach(auth()->user()->structures as $s)
                    <span class="badge-blue">{{ $s->acronym }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Filtres + Recherche --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base shrink-0">Projets & Porteurs</h3>
        </div>

        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('point-focal.dashboard') }}" class="flex flex-wrap gap-3 items-end">

                {{-- Recherche libre --}}
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Rechercher</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Nom, téléphone, email, titre de projet…"
                               class="form-input pl-9 py-2 text-sm w-full">
                    </div>
                </div>

                {{-- Filtre structure --}}
                <div class="w-48">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Structure</label>
                    <select name="structure_id" class="form-select py-2 text-sm">
                        <option value="">Toutes les structures</option>
                        @foreach($structures as $s)
                            <option value="{{ $s->id }}" {{ $filterSt == $s->id ? 'selected' : '' }}>
                                {{ $s->acronym }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtre statut --}}
                <div class="w-44">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Statut</label>
                    <select name="status" class="form-select py-2 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="not_started" {{ $filterStatus === 'not_started' ? 'selected' : '' }}>Non entamé</option>
                        <option value="draft"       {{ $filterStatus === 'draft'       ? 'selected' : '' }}>En cours</option>
                        <option value="submitted"   {{ $filterStatus === 'submitted'   ? 'selected' : '' }}>Soumis</option>
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary py-2 text-sm">Filtrer</button>
                    @if($search || $filterSt || $filterStatus)
                        <a href="{{ route('point-focal.dashboard') }}" class="btn-secondary py-2 text-sm">✕ Réinitialiser</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Résultat --}}
        @if($search || $filterSt || $filterStatus)
        <div class="px-5 py-2 text-xs text-gray-500 bg-blue-50 border-b border-blue-100">
            {{ $assignments->total() }} résultat(s) trouvé(s)
            @if($search) · recherche : <strong>{{ $search }}</strong> @endif
        </div>
        @endif

        {{-- Tableau --}}
        <div class="table-container rounded-none border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Porteur de projet</th>
                        <th>Contact</th>
                        <th>Titre du projet</th>
                        <th>Structure</th>
                        <th class="text-center">Statut</th>
                        <th>Domaine</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $a)
                    @php
                        $project     = $a->project;
                        $isSubmitted = $project?->status === 'submitted';
                        $isDraft     = $project?->status === 'draft';
                    @endphp
                    <tr>
                        {{-- Porteur --}}
                        <td>
                            <p class="font-medium text-gray-900 text-sm">{{ $a->porteur->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $a->porteur->email }}</p>
                        </td>

                        {{-- Téléphone --}}
                        <td>
                            @if($a->porteur->phone)
                                <p class="text-sm text-gray-600">{{ $a->porteur->phone }}</p>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Titre projet --}}
                        <td class="max-w-xs">
                            <p class="text-sm text-gray-800 leading-snug" title="{{ $a->title }}">
                                {{ Str::limit($a->title, 60) }}
                            </p>
                            @if($project?->scientific_domain)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $project->scientific_domain }}</p>
                            @endif
                        </td>

                        {{-- Structure --}}
                        <td>
                            <span class="badge-blue">{{ $a->structure->acronym }}</span>
                        </td>

                        {{-- Statut --}}
                        <td class="text-center">
                            @if($isSubmitted)
                                <span class="badge-green">✓ Soumis</span>
                            @elseif($isDraft)
                                <span class="badge-yellow">En cours</span>
                            @else
                                <span class="badge-gray">Non entamé</span>
                            @endif
                        </td>

                        {{-- Domaine --}}
                        <td>
                            @if($project?->scientific_domain)
                                <span class="text-xs text-gray-600">{{ $project->scientific_domain }}</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td>
                            @if($project)
                            <a href="{{ route('point-focal.projects.show', $project) }}"
                               class="text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                Voir contenu
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Aucun résultat trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($assignments->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $assignments->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
