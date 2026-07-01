@extends('layouts.app')
@section('title', 'Mon espace projet')
@section('page-title', \App\Models\User::roleLabel(auth()->user()->role))
@section('page-subtitle', 'SRI 2026 – Appel à communication')

@section('content')
<div class="space-y-4">

    {{-- ── Bannière bienvenue ──────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-6 text-white">
        <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
            <svg viewBox="0 0 200 200" class="w-64 h-64 text-white" fill="currentColor">
                <circle cx="150" cy="50" r="80"/><circle cx="50" cy="150" r="60"/>
            </svg>
        </div>
        <div class="relative flex items-center justify-between gap-4">
            <div>
                <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Bienvenue</p>
                <h2 class="text-xl font-extrabold leading-tight">{{ auth()->user()->name }}</h2>
                <p class="text-blue-200 text-sm mt-0.5">
                    {{ auth()->user()->structure?->acronym }}
                    @if(auth()->user()->structure?->name) · {{ auth()->user()->structure->name }} @endif
                </p>
            </div>
            <div class="hidden sm:flex w-12 h-12 bg-white/15 rounded-xl items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ── Stat cards ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([
            ['label' => 'Total assignés', 'val' => $tabCounts['tous'],      'bg' => 'bg-blue-50',    'fg' => 'text-blue-600',   'ring' => 'text-blue-400',
             'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Non entamés',    'val' => $tabCounts['pending'],   'bg' => 'bg-red-50',     'fg' => 'text-red-500',    'ring' => 'text-red-400',
             'icon'  => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'],
            ['label' => 'En cours',       'val' => $tabCounts['draft'],     'bg' => 'bg-amber-50',   'fg' => 'text-amber-600',  'ring' => 'text-amber-400',
             'icon'  => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125'],
            ['label' => 'Soumis',         'val' => $tabCounts['submitted'], 'bg' => 'bg-emerald-50', 'fg' => 'text-emerald-600','ring' => 'text-emerald-400',
             'icon'  => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $s)
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 {{ $s['bg'] }} rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 {{ $s['fg'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-extrabold text-gray-900 leading-none">{{ $s['val'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Tableau projets ──────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- En-tête + onglets --}}
        <div class="px-5 pt-4 pb-0 border-b border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Mes projets assignés</h3>
            </div>

            {{-- Onglets statut --}}
            @php
            $tabs = [
                'tous'      => 'Tous',
                'pending'   => 'Non entamés',
                'draft'     => 'En cours',
                'submitted' => 'Soumis',
            ];
            @endphp
            <div class="flex gap-0.5 overflow-x-auto">
                @foreach($tabs as $key => $label)
                @php $isActive = $tab === $key; @endphp
                <a href="{{ route('porteur.dashboard', ['tab' => $key, 'search' => $search]) }}"
                   class="flex items-center gap-1.5 px-3 py-2.5 text-xs font-medium border-b-2 whitespace-nowrap transition-all
                       {{ $isActive ? 'border-blue-600 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $label }}
                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] text-xs rounded-full px-1 font-semibold
                        {{ $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $tabCounts[$key] }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Barre de recherche --}}
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('porteur.dashboard') }}" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Rechercher par titre…"
                           class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" class="btn-primary text-xs py-2 px-3">Rechercher</button>
                @if($search)
                <a href="{{ route('porteur.dashboard', ['tab' => $tab]) }}" class="btn-secondary text-xs py-2 px-3">Effacer</a>
                @endif
            </form>
        </div>

        {{-- Contenu --}}
        @if($displayed->isEmpty())
        <div class="py-14 text-center">
            <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if($search)
                <p class="text-gray-500 text-sm">Aucun projet ne correspond à "<strong>{{ $search }}</strong>".</p>
                <a href="{{ route('porteur.dashboard', ['tab' => $tab]) }}" class="text-blue-600 text-xs hover:underline mt-1 inline-block">Effacer la recherche</a>
            @elseif($tab === 'tous')
                <p class="text-gray-600 font-medium text-sm">Aucun projet ne vous a encore été assigné.</p>
                <p class="text-gray-400 text-xs mt-1">Contactez la Direction de la Recherche.</p>
            @else
                <p class="text-gray-500 text-sm">Aucun projet dans cette catégorie.</p>
            @endif
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-5 text-xs text-gray-500 font-medium w-8">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Titre du projet</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Structure</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Statut</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($displayed->values() as $i => $assignment)
                    @php
                        $project     = $assignment->project;
                        $isSubmitted = $project?->status === 'submitted';
                        $isDraft     = $project && $project->status === 'draft';
                        $notStarted  = !$project;
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50/70 transition-colors">
                        <td class="py-3 px-5">
                            <span class="inline-flex w-6 h-6 rounded-lg items-center justify-center text-xs font-bold
                                {{ $isSubmitted ? 'bg-emerald-100 text-emerald-700' : ($isDraft ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500') }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="py-3 px-4 max-w-xs">
                            <p class="font-semibold text-gray-900 text-xs leading-snug">
                                {{ Str::limit($assignment->title, 75) }}
                            </p>
                            @if($project?->scientific_domain)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $project->scientific_domain }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $assignment->structure->acronym ?? $assignment->structure?->name ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($isSubmitted)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Soumis
                                </span>
                            @elseif($isDraft)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">En cours</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Non entamé</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $eyeIcon = '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
                            @endphp
                            @if($isSubmitted)
                                <a href="{{ route('porteur.projects.show', $project) }}"
                                   class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                                    {!! $eyeIcon !!} Voir
                                </a>
                            @elseif($isDraft)
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('porteur.projects.show', $project) }}"
                                       class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                                        {!! $eyeIcon !!} Voir
                                    </a>
                                    <a href="{{ route('porteur.projects.edit', $project) }}"
                                       class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('porteur.projects.submit', $project) }}"
                                          data-confirm="Soumettre définitivement ce projet ? Vous ne pourrez plus le modifier."
                                          data-confirm-title="Soumettre le projet"
                                          data-confirm-type="warning">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                            Soumettre
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('porteur.projects.create', $assignment) }}"
                                   class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
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

    {{-- ── Instructions ──────────────────────────────────────────── --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
        <svg class="w-5 h-5 shrink-0 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-blue-900 mb-1">Instructions</p>
            <ul class="text-xs text-blue-800 space-y-1 list-disc list-inside">
                <li>Remplissez complètement le formulaire de chaque projet assigné.</li>
                <li>Vous pouvez sauvegarder en brouillon et revenir plus tard.</li>
                <li>Une fois soumis, le formulaire ne peut plus être modifié.</li>
                <li>Le Comité Scientifique évaluera l'ensemble des projets soumis.</li>
            </ul>
        </div>
    </div>

</div>
@endsection
