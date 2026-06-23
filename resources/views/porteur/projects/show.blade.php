@extends('layouts.app')
@section('title', $project->assignment?->title ?? 'Mon projet')
@section('page-title', $project->assignment?->title ?? 'Mon projet')
@section('page-subtitle', 'Soumis – Lecture seule')

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="flex items-center gap-3">
        <span class="badge-green">Soumis</span>
        @if($project->selected)<span class="badge-blue">Sélectionné</span>@endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Responsable</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
            <div><p class="text-xs text-gray-500 mb-0.5">Nom</p><p class="font-medium">{{ $project->responsable_nom }}</p></div>
            <div><p class="text-xs text-gray-500 mb-0.5">Email</p><p>{{ $project->contact_email }}</p></div>
            @if($project->contact_phone)<div><p class="text-xs text-gray-500 mb-0.5">Téléphone</p><p>{{ $project->contact_phone }}</p></div>@endif
            <div><p class="text-xs text-gray-500 mb-0.5">Structure</p><p>{{ $project->structure?->name ?? '–' }}</p></div>
        </div>
    </div>

    @foreach(['summary' => 'Résumé', 'problematic' => 'Problématique', 'solution' => 'Solution', 'results' => 'Résultats attendus'] as $field => $label)
    @if($project->$field)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">{{ $label }}</h3>
        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $project->$field }}</p>
    </div>
    @endif
    @endforeach

    @if($project->collaborators->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Collaborateurs ({{ $project->collaborators->count() }})</h3>
        <div class="space-y-3">
            @foreach($project->collaborators as $collab)
            <div class="border border-gray-100 rounded-lg p-3 text-sm">
                <p class="font-medium">{{ $collab->fullName() }}</p>
                @if($collab->role_collaborateur)<p class="text-xs text-gray-500">{{ $collab->role_collaborateur }}</p>@endif
                @if($collab->institution)<p class="text-xs text-gray-400">{{ $collab->institution }}</p>@endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <a href="{{ route('porteur.dashboard') }}" class="btn-secondary text-sm inline-flex items-center gap-2">
        @include('components.icon', ['name' => 'arrow-left']) Retour au tableau de bord
    </a>
</div>
@endsection
