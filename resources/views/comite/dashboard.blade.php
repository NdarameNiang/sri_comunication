@extends('layouts.app')
@section('title', 'Sélection des projets')
@section('page-title', 'Comité Scientifique – Sélection')
@section('page-subtitle', 'Évaluation et sélection des projets soumis')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="card p-5 border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Projets soumis</p>
        </div>
        <div class="card p-5 border-l-4 border-emerald-500">
            <p class="text-2xl font-bold text-emerald-700">{{ $stats['selected'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Projets sélectionnés</p>
        </div>
        <div class="card p-5 border-l-4 border-amber-500">
            <p class="text-2xl font-bold text-amber-700">{{ $stats['sent'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Emails envoyés</p>
        </div>
    </div>

    {{-- Action envoi d'emails --}}
    @if($stats['selected'] > 0)
    <div class="card bg-emerald-50 border-emerald-200">
        <div class="card-body flex items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-emerald-900">
                    {{ $stats['selected'] }} projet(s) sélectionné(s)
                    @if($stats['sent'] > 0)
                        · {{ $stats['sent'] }} email(s) déjà envoyé(s)
                    @endif
                </p>
                <p class="text-sm text-emerald-700 mt-0.5">
                    Envoyez les notifications officielles à tous les porteurs des projets sélectionnés qui n'ont pas encore reçu leur email.
                </p>
            </div>
            <form method="POST" action="{{ route('comite.send-emails') }}"
                  data-confirm="Envoyer les emails officiels aux porteurs sélectionnés ? Cette action ne peut pas être annulée."
                  data-confirm-title="Envoi des notifications"
                  data-confirm-type="info">
                @csrf
                <button type="submit" class="btn-success whitespace-nowrap">
                    @include('components.icon', ['name' => 'mail'])
                    Envoyer les notifications
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Résumé par structure --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Résumé par structure</h3>
        </div>
        <div class="card-body grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($structures as $structure)
            @if($structure->submitted_count > 0)
            <div class="p-3 rounded-xl border border-gray-200 bg-gray-50">
                <span class="badge-blue">{{ $structure->acronym }}</span>
                <div class="mt-2 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Soumis</span>
                        <span class="font-semibold text-gray-900">{{ $structure->submitted_count }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Sélectionnés</span>
                        <span class="font-semibold text-emerald-700">{{ $structure->selected_count }}</span>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Liste des projets --}}
    <div class="card">
        <div class="card-header">
            <h3 class="section-title text-base">Projets soumis</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($projects as $project)
            <div class="px-6 py-5 {{ $project->selected ? 'bg-emerald-50/50' : '' }}">
                <div class="flex items-start gap-4">
                    {{-- Checkbox sélection --}}
                    <div class="shrink-0 pt-0.5">
                        <form method="POST" action="{{ route('comite.projects.toggle', $project) }}">
                            @csrf
                            <button type="submit" class="w-6 h-6 rounded-md border-2 flex items-center justify-center transition-all
                                {{ $project->selected
                                    ? 'bg-emerald-500 border-emerald-500 hover:bg-emerald-600'
                                    : 'border-gray-300 hover:border-emerald-400' }}"
                                title="{{ $project->selected ? 'Désélectionner' : 'Sélectionner' }}">
                                @if($project->selected)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                @endif
                            </button>
                        </form>
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <a href="{{ route('comite.projects.show', $project) }}" class="font-semibold text-gray-900 hover:text-blue-700 text-sm leading-snug">
                                    {{ $project->assignment?->title }}
                                </a>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="badge-blue text-xs">{{ $project->structure->acronym }}</span>
                                    <span class="text-xs text-gray-500">{{ $project->scientific_domain }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($project->selected)
                                    <span class="badge-green">Sélectionné</span>
                                    @if($project->email_sent_at)
                                        <span class="badge-blue text-xs">Email envoyé</span>
                                    @endif
                                @endif
                                <a href="{{ route('comite.projects.show', $project) }}" class="btn-secondary text-xs px-3 py-1.5">
                                    @include('components.icon', ['name' => 'eye'])
                                    Voir
                                </a>
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                            <div>
                                <p class="text-gray-400">Porteur</p>
                                <p class="font-medium text-gray-700">{{ $project->porteur->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Type(s)</p>
                                <p class="font-medium text-gray-700">
                                    {{ implode(', ', array_map(fn($t) => \App\Models\Project::projectTypeLabels()[$t] ?? $t, $project->project_types ?? [])) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400">Maturité</p>
                                <p class="font-medium text-gray-700">{{ \App\Models\Project::maturityLabels()[$project->maturity_level] ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Format souhaité</p>
                                <p class="font-medium text-gray-700">
                                    {{ $project->presentation_formats ? implode(', ', array_map(fn($f) => \App\Models\Project::presentationLabels()[$f] ?? $f, $project->presentation_formats)) : '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <p class="text-gray-400 text-sm">Aucun projet soumis pour le moment.</p>
                <p class="text-gray-300 text-xs mt-1">Les projets apparaîtront ici une fois que les porteurs les auront soumis.</p>
            </div>
            @endforelse
        </div>
        @if($projects->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
