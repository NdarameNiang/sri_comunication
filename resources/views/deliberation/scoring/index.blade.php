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
                    <th class="px-5 py-3 font-medium">Projet</th>
                    <th class="px-5 py-3 font-medium">Auteur</th>
                    <th class="px-5 py-3 font-medium">Établissement</th>
                    <th class="px-5 py-3 font-medium">Format</th>
                    <th class="px-5 py-3 font-medium">État</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($projects as $project)
                @php $myScore = $myScores->get($project->id); @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-5 py-4 max-w-xs">
                        <p class="font-semibold text-gray-900 leading-snug line-clamp-2">{{ $project->assignment?->title ?? 'Projet sans titre' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-700 font-medium text-xs">{{ $project->porteur?->name ?? $project->responsable_nom }}</p>
                        @if($project->porteur && $project->responsable_nom && $project->porteur->name !== $project->responsable_nom)
                        <p class="text-gray-400 text-xs mt-0.5">{{ $project->responsable_nom }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge-blue text-xs">{{ $project->structure?->acronym ?? '–' }}</span>
                        <p class="text-xs text-gray-400 mt-1 leading-tight">{{ $project->structure?->name }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $project->isApprofondi() ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                            {{ $project->isApprofondi() ? 'Approfondi' : 'Standard' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if(!$myScore)
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                Non commencée
                            </span>
                        @elseif($myScore->isSubmitted())
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Soumise
                            </span>
                            @if($isGlobal && $myScore->evaluator)
                            <p class="text-xs text-gray-400 mt-1">par {{ $myScore->evaluator->name }}</p>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                                Brouillon
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('deliberation.scoring.show', $project) }}"
                           class="{{ $myScore ? 'btn-secondary' : 'btn-primary' }} text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                            @if(!$myScore)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                            Noter
                            @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                            Modifier
                            @endif
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-14 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <p class="text-gray-500 font-medium text-sm">Aucun projet trouvé</p>
                        <p class="text-gray-400 text-xs mt-1">Essayez d'ajuster vos filtres.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $projects->links() }}
</div>
@endsection
