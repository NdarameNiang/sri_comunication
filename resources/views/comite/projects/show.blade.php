@extends('layouts.app')
@section('title', 'Projet – ' . ($project->assignment?->title ?? $project->responsable_nom))
@section('page-title', $project->assignment?->title ?? 'Projet')
@section('page-subtitle', $project->structure?->name ?? '')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Barre d'actions ------------------------------------------------ --}}
    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('comite.dashboard') }}" class="btn-secondary text-sm inline-flex items-center gap-1.5">
            ← Retour
        </a>
        @if($project->selected && $project->email_sent_at)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Sélectionné
            </span>
            <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                Email envoyé le {{ $project->email_sent_at->format('d/m/Y') }} – sélection verrouillée
            </span>
        @else
            <form method="POST" action="{{ route('comite.projects.toggle', $project) }}">
                @csrf
                <button type="submit" class="{{ $project->selected ? 'btn-danger' : 'btn-success' }} text-sm inline-flex items-center gap-1.5">
                    @if($project->selected)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Désélectionner
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Sélectionner ce projet
                    @endif
                </button>
            </form>
            @if($project->selected)
            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Sélectionné
            </span>
            @endif
        @endif
    </div>

    {{-- En-tête projet ------------------------------------------------- --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 pt-6 pb-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-base font-bold text-gray-900 leading-snug">{{ $project->assignment?->title ?? 'Projet sans titre' }}</h2>
                <p class="text-sm text-gray-400 mt-0.5">{{ $project->structure?->name ?? '–' }}</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="{{ $project->status === 'submitted' ? 'inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200' : 'inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ $project->status === 'submitted' ? 'Soumis' : 'Brouillon' }}
                    </span>
                    @foreach($project->project_types ?? [] as $type)
                    <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">{{ \App\Models\Project::projectTypeLabels()[$type] ?? $type }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100">
            @foreach([
                ['Responsable', $project->responsable_nom],
                ['Email', $project->contact_email],
                ['Maturité', \App\Models\Project::maturityLabels()[$project->maturity_level] ?? '–'],
                ['Domaine', $project->scientific_domain ?? '–'],
            ] as [$label, $value])
            <div class="px-4 py-3">
                <p class="text-xs text-gray-400">{{ $label }}</p>
                <p class="text-sm font-medium text-gray-800 mt-0.5 truncate" title="{{ $value }}">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Sections texte ------------------------------------------------- --}}
    @php
    $sections = [
        ['num' => '01', 'label' => 'Résumé',            'field' => 'summary',     'color' => 'border-indigo-300'],
        ['num' => '02', 'label' => 'Problématique',     'field' => 'problematic', 'color' => 'border-amber-300'],
        ['num' => '03', 'label' => 'Solution / Innovation','field' => 'solution', 'color' => 'border-emerald-300'],
        ['num' => '04', 'label' => 'Résultats attendus', 'field' => 'results',    'color' => 'border-blue-300'],
    ];
    @endphp
    @foreach($sections as $s)
    @if($project->{$s['field']})
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <span class="text-xs font-bold text-gray-300 font-mono tracking-widest">{{ $s['num'] }}</span>
            <h3 class="text-sm font-semibold text-gray-700">{{ $s['label'] }}</h3>
        </div>
        <div class="px-5 py-4 border-l-2 {{ $s['color'] }} mx-5 my-4 rounded-sm">
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $project->{$s['field']} }}</p>
        </div>
    </div>
    @endif
    @endforeach

    {{-- Valorisation & Impact ------------------------------------------ --}}
    @if($project->protection_types || $project->impact_types || $project->presentation_formats)
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-sm font-semibold text-gray-700">Valorisation & Impact</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
            @foreach([
                ['Protection', $project->protection_types, \App\Models\Project::protectionLabels(), 'bg-amber-50 text-amber-700'],
                ['Impact', $project->impact_types, \App\Models\Project::impactLabels(), 'bg-emerald-50 text-emerald-700'],
                ['Présentation', $project->presentation_formats, \App\Models\Project::presentationLabels(), 'bg-blue-50 text-blue-700'],
            ] as [$label, $values, $map, $cls])
            @if($values)
            <div class="px-5 py-4">
                <p class="text-xs text-gray-400 mb-2">{{ $label }}</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach((array)$values as $v)
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $cls }}">{{ $map[$v] ?? $v }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @if($project->logistic_needs)
        <div class="border-t border-gray-100 px-5 py-4">
            <p class="text-xs text-gray-400 mb-1">Besoins logistiques</p>
            <p class="text-sm text-gray-700">{{ $project->logistic_needs }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Collaborateurs ------------------------------------------------- --}}
    @if($project->collaborators && $project->collaborators->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Collaborateurs</h3>
            <span class="text-xs text-gray-400">{{ $project->collaborators->count() }} membre(s)</span>
        </div>
        <div class="divide-y divide-gray-50 px-2 py-2">
            @foreach($project->collaborators as $collab)
            <div class="flex items-center gap-3 px-3 py-2.5">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($collab->prenom ?? $collab->nom ?? '?', 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $collab->fullName() }}</p>
                    <p class="text-xs text-gray-400">{{ $collab->institution ?? $collab->role_collaborateur ?? '' }}</p>
                </div>
                @if($collab->email)<p class="text-xs text-gray-400 hidden sm:block">{{ $collab->email }}</p>@endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
