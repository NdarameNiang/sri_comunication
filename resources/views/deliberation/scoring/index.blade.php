@extends('layouts.app')
@section('title', 'Notation des projets')
@section('page-title', 'Notation des projets')
@section('page-subtitle', 'Comité de délibération')

@section('content')
<div class="space-y-4">
    @if($isGlobal)
    <div class="flex items-center gap-2.5 p-3 bg-indigo-50 border border-indigo-200 rounded-xl text-sm text-indigo-800">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <span>Délibération en mode <strong>note unique partagée</strong> : la note enregistrée par un membre du comité vaut pour tout le monde et reste modifiable par n'importe qui.</span>
    </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="{{ route('deliberation.scoring.index') }}" class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Titre du projet ou auteur…"
                    class="form-input w-full text-sm">
            </div>
            <div class="w-56">
                <label class="block text-xs font-medium text-gray-500 mb-1">Établissement</label>
                <select name="structure_id" class="form-input w-full text-sm">
                    <option value="">Tous les établissements</option>
                    @foreach($structures as $structure)
                        <option value="{{ $structure->id }}" {{ request('structure_id') == $structure->id ? 'selected' : '' }}>
                            {{ $structure->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-44">
                <label class="block text-xs font-medium text-gray-500 mb-1">État notation</label>
                <select name="notation" class="form-input w-full text-sm">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('notation') === 'pending' ? 'selected' : '' }}>Non commencée</option>
                    <option value="draft"   {{ request('notation') === 'draft'   ? 'selected' : '' }}>Brouillon</option>
                    <option value="done"    {{ request('notation') === 'done'    ? 'selected' : '' }}>Soumise</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary text-sm py-2 px-4">Filtrer</button>
                @if(request()->hasAny(['search','structure_id','notation']))
                <a href="{{ route('deliberation.scoring.index') }}" class="btn-secondary text-sm py-2 px-3">Réinitialiser</a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <p class="text-sm text-gray-500">
                <span class="font-semibold text-gray-800">{{ $projects->total() }}</span> projet{{ $projects->total() > 1 ? 's' : '' }}
                @if(request()->hasAny(['search','structure_id','notation']))<span class="text-xs text-gray-400 ml-1">(filtrés)</span>@endif
            </p>
        </div>
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50/50">
                <tr class="text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3">Projet</th>
                    <th class="px-5 py-3">Structure</th>
                    <th class="px-5 py-3">Format</th>
                    <th class="px-5 py-3">{{ $isGlobal ? 'Notation' : 'Ma notation' }}</th>
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
                            @if($isGlobal && $myScore->evaluator)
                            <p class="text-xs text-gray-400 mt-1">Par {{ $myScore->evaluator->name }}</p>
                            @endif
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
