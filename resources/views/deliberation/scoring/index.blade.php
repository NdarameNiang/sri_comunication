@extends('layouts.app')
@section('title', 'Notation des projets')
@section('page-title', 'Notation des projets')
@section('page-subtitle', 'Comité de délibération')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50/50">
                <tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3">Projet</th>
                    <th class="px-5 py-3">Structure</th>
                    <th class="px-5 py-3">Format</th>
                    <th class="px-5 py-3">Ma notation</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($projects as $project)
                @php $myScore = $myScores->get($project->id); @endphp
                <tr>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $project->assignment?->title ?? 'Projet sans titre' }}</p>
                        <p class="text-xs text-gray-400">{{ $project->responsable_nom }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $project->structure?->name ?? '–' }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $project->isApprofondi() ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                            {{ $project->isApprofondi() ? 'Approfondi' : 'Standard' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        @if(!$myScore)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-50 text-gray-500 border border-gray-200">Non commencée</span>
                        @elseif($myScore->isSubmitted())
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Soumise
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100">Brouillon</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('deliberation.scoring.show', $project) }}" class="btn-secondary text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                            {{ $myScore ? 'Modifier' : 'Noter' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">Aucun projet soumis pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $projects->links() }}
</div>
@endsection
