@extends('layouts.app')
@section('title', 'Mon espace projet')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'SRI 2026 – Appel à communication')

@section('content')
<div class="space-y-6">

    {{-- ── Bannière bienvenue ─────────────────────────────────────── --}}
    <div class="dash-banner">
        {{-- motif décoratif --}}
        <div class="absolute right-0 top-0 w-64 h-full opacity-10 overflow-hidden pointer-events-none">
            <svg viewBox="0 0 200 200" class="absolute -right-10 -top-10 w-72 h-72 text-white" fill="currentColor">
                <circle cx="150" cy="50" r="80"/><circle cx="50" cy="150" r="60"/>
            </svg>
        </div>
        <div class="relative flex items-start justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Bienvenue</p>
                <h2 class="text-2xl font-extrabold text-white leading-tight">{{ auth()->user()->name }}</h2>
                <p class="text-blue-200 text-sm mt-1">
                    {{ auth()->user()->structure?->acronym }} · {{ auth()->user()->structure?->name }}
                </p>
                <p class="text-white/50 text-xs mt-2">Remplissez et soumettez vos projets assignés avant la date limite.</p>
            </div>
            <div class="hidden sm:flex w-14 h-14 bg-white/15 rounded-2xl items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ── Stat cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total assignés', 'val' => $tabCounts['tous'],      'bg' => 'bg-blue-100',   'fg' => 'text-blue-600',    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Non entamés',    'val' => $tabCounts['pending'],   'bg' => 'bg-red-100',    'fg' => 'text-red-500',     'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'],
            ['label' => 'En cours',       'val' => $tabCounts['draft'],     'bg' => 'bg-amber-100',  'fg' => 'text-amber-600',   'icon' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125'],
            ['label' => 'Soumis',         'val' => $tabCounts['submitted'], 'bg' => 'bg-emerald-100','fg' => 'text-emerald-600',  'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $s)
        <div class="stat-card flex items-center gap-4">
            <div class="stat-icon {{ $s['bg'] }} shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $s['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $s['val'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Tableau par onglets ─────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Mes projets assignés</h3>
        </div>

        @php
        $tabs = [
            'tous'      => 'Tous',
            'pending'   => 'Non entamés',
            'draft'     => 'En cours',
            'submitted' => 'Soumission terminée',
        ];
        @endphp
        <div class="flex gap-1 px-4 pt-2 border-b border-gray-100 overflow-x-auto">
            @foreach($tabs as $key => $label)
            @php $isActive = $tab === $key; @endphp
            <a href="{{ route('porteur.dashboard', ['tab' => $key]) }}"
               class="flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 whitespace-nowrap transition-all
                   {{ $isActive ? 'border-blue-600 text-blue-700 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                {{ $label }}
                <span class="inline-flex items-center justify-center text-xs rounded-full px-1.5 py-0.5 font-semibold
                    {{ $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $tabCounts[$key] }}
                </span>
            </a>
            @endforeach
        </div>

        @if($displayed->isEmpty())
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if($tab === 'tous')
                <p class="text-gray-600 font-medium text-sm">Aucun projet ne vous a encore été assigné.</p>
                <p class="text-gray-400 text-xs mt-1">Contactez la Direction de la Recherche.</p>
            @else
                <p class="text-gray-500 text-sm">Aucun projet dans cette catégorie.</p>
            @endif
        </div>
        @else
        <div class="table-container rounded-none border-0">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th>Titre du projet</th>
                        <th>Structure</th>
                        <th class="text-center">Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($displayed as $i => $assignment)
                    @php
                        $project     = $assignment->project;
                        $isSubmitted = $project?->status === 'submitted';
                        $isDraft     = $project && $project->status === 'draft';
                        $notStarted  = ! $project;
                    @endphp
                    <tr>
                        <td>
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold
                                {{ $isSubmitted ? 'bg-emerald-100 text-emerald-700' : ($isDraft ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500') }}">
                                {{ $i + 1 }}
                            </div>
                        </td>
                        <td class="max-w-xs">
                            <p class="font-semibold text-gray-900 text-sm leading-snug" title="{{ $assignment->title }}">
                                {{ Str::limit($assignment->title, 70) }}
                            </p>
                            @if($project)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $project->scientific_domain }}</p>
                            @endif
                        </td>
                        <td><span class="badge-blue">{{ $assignment->structure->acronym }}</span></td>
                        <td class="text-center">
                            @if($isSubmitted)
                                <span class="badge-green">✓ Soumis</span>
                            @elseif($isDraft)
                                <span class="badge-yellow">En cours</span>
                            @else
                                <span class="badge-gray">Non entamé</span>
                            @endif
                        </td>
                        <td>
                            @if($isSubmitted)
                                <a href="{{ route('porteur.projects.show', $project) }}" class="btn-secondary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                    @include('components.icon', ['name' => 'eye'])
                                    Consulter
                                </a>
                            @elseif($isDraft)
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('porteur.projects.edit', $project) }}" class="btn-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                        @include('components.icon', ['name' => 'pencil'])
                                        Continuer
                                    </a>
                                    <form method="POST" action="{{ route('porteur.projects.submit', $project) }}"
                                          data-confirm="Soumettre définitivement ce projet ? Vous ne pourrez plus le modifier après cette action."
                                          data-confirm-title="Soumettre le projet"
                                          data-confirm-type="warning">
                                        @csrf
                                        <button type="submit" class="btn-success text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                            @include('components.icon', ['name' => 'check'])
                                            Soumettre
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('porteur.projects.create', $assignment) }}" class="btn-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                    @include('components.icon', ['name' => 'plus'])
                                    Remplir
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Instructions --}}
    <div class="alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="font-semibold text-blue-900 mb-1.5">Instructions</p>
            <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                <li>Remplissez complètement le formulaire de chaque projet assigné.</li>
                <li>Vous pouvez sauvegarder en brouillon et revenir plus tard.</li>
                <li>Une fois soumis, le formulaire ne peut plus être modifié.</li>
                <li>Le Comité Scientifique évaluera l'ensemble des projets soumis.</li>
            </ul>
        </div>
    </div>

</div>
@endsection
