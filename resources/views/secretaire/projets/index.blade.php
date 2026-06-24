@extends('layouts.app')
@section('title', 'Projets soumis')
@section('page-title', 'Projets soumis')
@section('page-subtitle', $projects->total() . ' projet(s)')

@section('content')
<div class="space-y-4">

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Porteur, structure, résumé…"
                       class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Domaine</label>
                <select name="domain" class="input-field text-sm">
                    <option value="">Tous les domaines</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain }}" {{ request('domain') === $domain ? 'selected' : '' }}>{{ $domain }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sélection</label>
                <select name="selected" class="input-field text-sm">
                    <option value="">Tous</option>
                    <option value="1" {{ request('selected') === '1' ? 'selected' : '' }}>Sélectionnés</option>
                    <option value="0" {{ request('selected') === '0' ? 'selected' : '' }}>Non sélectionnés</option>
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            <a href="{{ route('secretaire.projets.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
        </form>
    </div>

    {{-- Export --}}
    <div>
        <a href="{{ route('secretaire.projets.export') }}"
           class="btn-secondary text-sm inline-flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Exporter CSV
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">{{ $projects->total() }} projet(s) soumis</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Porteur / Responsable</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Structure</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Domaine</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Types</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Maturité</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Statut</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $i => $project)
                    @php
                        $typeLabels = \App\Models\Project::projectTypeLabels();
                        $maturityLabels = \App\Models\Project::maturityLabels();
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-400 text-xs">{{ $projects->firstItem() + $i }}</td>
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-900">{{ $project->porteur?->name ?? '–' }}</p>
                            <p class="text-xs text-gray-500">{{ $project->responsable_nom }}</p>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $project->structure?->name ?? '–' }}</td>
                        <td class="py-3 px-4 text-gray-600 text-xs">{{ $project->scientific_domain }}</td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach((array)($project->project_types ?? []) as $type)
                                    <span class="text-xs px-1.5 py-0.5 rounded bg-blue-50 text-blue-700">{{ $typeLabels[$type] ?? $type }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600 text-xs">
                            {{ $project->maturity_level ? ($maturityLabels[$project->maturity_level] ?? $project->maturity_level) : '–' }}
                        </td>
                        <td class="py-3 px-4">
                            @if($project->selected)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">Sélectionné</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">En attente</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('secretaire.projets.show', $project) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400 text-sm">Aucun projet soumis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
