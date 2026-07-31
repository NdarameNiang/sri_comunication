@extends('layouts.app')
@section('title', 'Projets soumis')
@section('page-title', 'Projets soumis')
@section('page-subtitle', 'Consultation en lecture seule')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou email du porteur…" class="input-field text-sm flex-1">
            <button type="submit" class="btn-primary text-sm">Rechercher</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="divide-y divide-gray-50">
            @forelse($projects as $project)
            <a href="{{ route('generic.projects.show', $project) }}" class="flex items-center justify-between gap-3 px-5 py-4 hover:bg-gray-50">
                <div class="min-w-0">
                    <p class="font-medium text-gray-900 text-sm truncate">{{ $project->assignment?->title ?? 'Projet sans titre' }}</p>
                    <p class="text-xs text-gray-400">{{ $project->structure?->name ?? '–' }} · {{ $project->porteur?->name }}</p>
                </div>
                @if($project->selected)
                <span class="badge-blue shrink-0">Sélectionné</span>
                @endif
            </a>
            @empty
            <div class="px-5 py-10 text-center text-gray-400 text-sm">Aucun projet soumis pour le moment.</div>
            @endforelse
        </div>
    </div>

    {{ $projects->links() }}
</div>
@endsection
