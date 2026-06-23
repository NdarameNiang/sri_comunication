@extends('layouts.app')
@section('title', 'Mon espace projet')
@section('page-title', 'Mon espace projet')
@section('page-subtitle', 'SRI 2026 – Appel à communication')

@section('content')
<div class="space-y-6">

    {{-- Bienvenue --}}
    <div class="rounded-xl p-6" style="background: linear-gradient(135deg, #1e293b, #334155);color:#f1f5f9;">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold mb-1">Bienvenue, {{ auth()->user()->name }}</h2>
                <p class="text-sm" style="color:#94a3b8;">
                    Structure : <strong style="color:#f1f5f9;">{{ auth()->user()->structure?->acronym }} – {{ auth()->user()->structure?->name }}</strong>
                </p>
                <p class="text-xs mt-1" style="color:#64748b;">Remplissez le formulaire de chaque projet assigné et soumettez-le avant la date limite.</p>
            </div>
            <div class="hidden sm:flex w-16 h-16 bg-white/10 rounded-xl items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- Mini stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total assignés', 'val' => $tabCounts['tous'],      'bar' => 'bg-blue-500'],
            ['label' => 'Non entamés',    'val' => $tabCounts['pending'],   'bar' => 'bg-red-400'],
            ['label' => 'En cours',       'val' => $tabCounts['draft'],     'bar' => 'bg-amber-400'],
            ['label' => 'Soumis',         'val' => $tabCounts['submitted'], 'bar' => 'bg-emerald-500'],
        ] as $s)
        <div class="card p-5">
            <p class="text-2xl font-bold text-gray-900">{{ $s['val'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $s['label'] }}</p>
            <div class="mt-3 h-1 rounded-full {{ $s['bar'] }} opacity-30"></div>
        </div>
        @endforeach
    </div>

    {{-- Tableau par onglets --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Mes projets assignés</h3>
        </div>

        {{-- Onglets --}}
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

        {{-- Tableau --}}
        @if($displayed->isEmpty())
        <div class="px-6 py-14 text-center">
            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if($tab === 'tous')
                <p class="text-gray-500 text-sm">Aucun projet ne vous a encore été assigné.</p>
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
                        {{-- Numéro --}}
                        <td>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $isSubmitted ? 'bg-emerald-100 text-emerald-700' : ($isDraft ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500') }}">
                                {{ $i + 1 }}
                            </div>
                        </td>

                        {{-- Titre --}}
                        <td class="max-w-xs">
                            <p class="font-medium text-gray-900 text-sm leading-snug" title="{{ $assignment->title }}">
                                {{ Str::limit($assignment->title, 70) }}
                            </p>
                            @if($project)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $project->scientific_domain }}</p>
                            @endif
                        </td>

                        {{-- Structure --}}
                        <td>
                            <span class="badge-blue">{{ $assignment->structure->acronym }}</span>
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

                        {{-- Actions --}}
                        <td>
                            @if($isSubmitted)
                                <a href="{{ route('porteur.projects.show', $project) }}"
                                   class="btn-secondary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                    @include('components.icon', ['name' => 'eye'])
                                    Consulter
                                </a>
                            @elseif($isDraft)
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('porteur.projects.edit', $project) }}"
                                       class="btn-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                        @include('components.icon', ['name' => 'pencil'])
                                        Continuer
                                    </a>
                                    <form method="POST" action="{{ route('porteur.projects.submit', $project) }}"
                                          data-confirm="Soumettre définitivement ce projet ? Vous ne pourrez plus le modifier après cette action."
                                          data-confirm-title="Soumettre le projet"
                                          data-confirm-type="warning">
                                        @csrf
                                        <button type="submit"
                                                class="btn-success text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                            @include('components.icon', ['name' => 'check'])
                                            Soumettre
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('porteur.projects.create', $assignment) }}"
                                   class="btn-primary text-xs px-3 py-1.5 inline-flex items-center gap-1">
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
    <div class="card bg-blue-50 border-blue-100">
        <div class="card-body">
            <h4 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Instructions
            </h4>
            <ul class="text-sm text-blue-800 space-y-1.5 list-disc list-inside">
                <li>Remplissez complètement le formulaire de chaque projet assigné.</li>
                <li>Vous pouvez sauvegarder en brouillon et revenir plus tard.</li>
                <li>Une fois soumis, le formulaire ne peut plus être modifié.</li>
                <li>Le Comité Scientifique évaluera l'ensemble des projets soumis.</li>
            </ul>
        </div>
    </div>

</div>
@endsection
