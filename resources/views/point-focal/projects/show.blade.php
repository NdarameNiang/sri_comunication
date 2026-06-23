@extends('layouts.app')
@section('title', $project->assignment?->title)
@section('page-title', $project->assignment?->title)
@section('page-subtitle', 'Consultation du contenu – Observateur')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Statut --}}
    <div class="flex items-center gap-3">
        @php
            $statuts = ['submitted' => ['Soumis', 'badge-green'], 'draft' => ['En cours', 'badge-yellow'], 'pending' => ['Non entamé', 'badge-gray']];
            [$label, $class] = $statuts[$project->status ?? 'pending'] ?? ['–', 'badge-gray'];
        @endphp
        <span class="{{ $class }}">{{ $label }}</span>
        @if($project->selected)
        <span class="badge-blue">Sélectionné</span>
        @endif
    </div>

    {{-- Infos porteur --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Responsable</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
            <div><p class="text-xs text-gray-500 mb-0.5">Nom</p><p class="font-medium">{{ $project->responsable_nom ?? $project->porteur?->name }}</p></div>
            <div><p class="text-xs text-gray-500 mb-0.5">Email</p><p>{{ $project->contact_email ?? $project->porteur?->email }}</p></div>
            @if($project->contact_phone)
            <div><p class="text-xs text-gray-500 mb-0.5">Téléphone</p><p>{{ $project->contact_phone }}</p></div>
            @endif
            <div><p class="text-xs text-gray-500 mb-0.5">Structure</p><p>{{ $project->structure?->name ?? '–' }}</p></div>
            @if($project->scientific_domain)
            <div><p class="text-xs text-gray-500 mb-0.5">Domaine scientifique</p><p>{{ $project->scientific_domain }}</p></div>
            @endif
            @if($project->maturity_level)
            <div><p class="text-xs text-gray-500 mb-0.5">Maturité</p><p>{{ $project->maturity_level }}</p></div>
            @endif
        </div>
    </div>

    {{-- Contenu --}}
    @foreach(['summary' => 'Résumé', 'problematic' => 'Problématique', 'solution' => 'Solution proposée', 'results' => 'Résultats attendus'] as $field => $label)
    @if($project->$field)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">{{ $label }}</h3>
        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $project->$field }}</p>
    </div>
    @endif
    @endforeach

    {{-- Listes --}}
    @php
    $listFields = [
        'project_types'       => 'Types de projet',
        'protection_types'    => 'Protections',
        'valorisation_types'  => 'Valorisations',
        'impact_types'        => 'Impacts',
        'presentation_formats'=> 'Formats de présentation',
    ];
    @endphp
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Caractéristiques</h3>
        <div class="space-y-3 text-sm">
            @foreach($listFields as $field => $label)
            @if($project->$field && count($project->$field) > 0)
            <div>
                <p class="text-xs text-gray-500 mb-1">{{ $label }}</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($project->$field as $v)
                    <span class="bg-slate-100 text-slate-700 text-xs px-2 py-0.5 rounded-full">{{ $v }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Collaborateurs --}}
    @if($project->collaborators && $project->collaborators->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Collaborateurs ({{ $project->collaborators->count() }})</h3>
        <div class="space-y-3">
            @foreach($project->collaborators as $collab)
            <div class="border border-gray-100 rounded-lg p-3 text-sm">
                <p class="font-medium text-gray-900">{{ $collab->fullName() }}</p>
                @if($collab->role_collaborateur)<p class="text-xs text-gray-500">{{ $collab->role_collaborateur }}</p>@endif
                @if($collab->institution)<p class="text-xs text-gray-400">{{ $collab->institution }}</p>@endif
                @if($collab->email)<p class="text-xs text-gray-400">{{ $collab->email }}</p>@endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('point-focal.dashboard') }}" class="btn-secondary text-sm inline-flex items-center gap-2">
        @include('components.icon', ['name' => 'arrow-left']) Retour au tableau de bord
    </a>
</div>
@endsection
