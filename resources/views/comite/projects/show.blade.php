@extends('layouts.app')
@section('title', 'Détail du projet')
@section('page-title', 'Détail du projet')
@section('page-subtitle', $project->assignment?->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('comite.dashboard') }}" class="btn-secondary text-sm">
            @include('components.icon', ['name' => 'arrow-left'])
            Retour
        </a>
        <form method="POST" action="{{ route('comite.projects.toggle', $project) }}">
            @csrf
            <button type="submit" class="{{ $project->selected ? 'btn-danger' : 'btn-success' }} text-sm">
                @if($project->selected)
                    @include('components.icon', ['name' => 'x'])
                    Désélectionner
                @else
                    @include('components.icon', ['name' => 'check'])
                    Sélectionner ce projet
                @endif
            </button>
        </form>
        @if($project->selected)
            <span class="badge-green">Sélectionné</span>
            @if($project->email_sent_at)
                <span class="badge-blue">Email envoyé le {{ $project->email_sent_at->format('d/m/Y à H:i') }}</span>
            @endif
        @endif
    </div>

    {{-- Infos générales --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Informations générales</h3>
            <span class="{{ $project->status === 'submitted' ? 'badge-green' : 'badge-yellow' }}">
                {{ $project->status === 'submitted' ? 'Soumis' : 'Brouillon' }}
            </span>
        </div>
        <div class="card-body grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-400 mb-1">Structure</p>
                <span class="badge-blue">{{ $project->structure->acronym }}</span>
                <p class="text-sm text-gray-700 mt-1">{{ $project->structure->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Responsable</p>
                <p class="font-semibold text-gray-900">{{ $project->responsable_nom }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Contact</p>
                <p class="text-sm text-gray-700">{{ $project->contact_email }}</p>
                @if($project->contact_phone)<p class="text-sm text-gray-500">{{ $project->contact_phone }}</p>@endif
            </div>
            <div class="col-span-2 sm:col-span-3">
                <p class="text-xs text-gray-400 mb-1">Titre du projet</p>
                <p class="font-semibold text-gray-900 text-base">{{ $project->assignment?->title }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Domaine scientifique</p>
                <p class="text-sm font-medium text-gray-900">{{ $project->scientific_domain }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Type(s)</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($project->project_types ?? [] as $type)
                        <span class="badge-blue text-xs">{{ \App\Models\Project::projectTypeLabels()[$type] ?? $type }}</span>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Maturité</p>
                <p class="text-sm font-medium text-gray-900">{{ \App\Models\Project::maturityLabels()[$project->maturity_level] ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Description --}}
    <div class="card">
        <div class="card-header"><h3 class="section-title text-base">Description synthétique</h3></div>
        <div class="card-body space-y-4">
            @foreach([['Résumé', $project->summary], ['Problématique', $project->problematic], ['Solution / Innovation', $project->solution]] as [$label, $content])
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $label }}</p>
                <p class="text-sm text-gray-800 leading-relaxed">{{ $content }}</p>
            </div>
            @endforeach
            @if($project->results)
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Résultats obtenus</p>
                <p class="text-sm text-gray-800 leading-relaxed">{{ $project->results }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Valorisation, Impact, Présentation --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="card">
            <div class="card-header"><h3 class="text-sm font-semibold">Protection</h3></div>
            <div class="card-body">
                <div class="flex flex-wrap gap-1.5">
                    @forelse($project->protection_types ?? [] as $type)
                        <span class="badge-purple text-xs">{{ \App\Models\Project::protectionLabels()[$type] ?? $type }}</span>
                    @empty
                        <p class="text-xs text-gray-400">—</p>
                    @endforelse
                </div>
                @if($project->protection_autres)
                    <p class="text-xs text-gray-500 mt-2">{{ $project->protection_autres }}</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="text-sm font-semibold">Impact attendu</h3></div>
            <div class="card-body">
                <div class="flex flex-wrap gap-1.5">
                    @forelse($project->impact_types ?? [] as $type)
                        <span class="badge-blue text-xs">{{ \App\Models\Project::impactLabels()[$type] ?? $type }}</span>
                    @empty
                        <p class="text-xs text-gray-400">—</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="text-sm font-semibold">Présentation</h3></div>
            <div class="card-body">
                <div class="flex flex-wrap gap-1.5">
                    @forelse($project->presentation_formats ?? [] as $fmt)
                        <span class="badge-yellow text-xs">{{ \App\Models\Project::presentationLabels()[$fmt] ?? $fmt }}</span>
                    @empty
                        <p class="text-xs text-gray-400">—</p>
                    @endforelse
                </div>
                @if($project->logistic_needs)
                    <p class="text-xs text-gray-500 mt-2">{{ $project->logistic_needs }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Collaborateurs --}}
    @if($project->collaborators && $project->collaborators->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Collaborateurs ({{ $project->collaborators->count() }})</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
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
</div>
@endsection
