@extends('layouts.app')
@section('title', 'Porteurs de projet')
@section('page-title', 'Porteurs de projet')
@section('page-subtitle', 'Gestion par le Comité Scientifique')

@section('content')
<div class="space-y-4">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            </div>
            <div>
                <p class="text-xl font-extrabold text-gray-900 leading-none">{{ $porteurs->total() }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Total porteurs</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xl font-extrabold text-emerald-700 leading-none">{{ $porteurs->getCollection()->filter(fn($p) => $p->projectAssignments->where('project.status','submitted')->count() > 0)->count() }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Ont soumis</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xl font-extrabold text-amber-600 leading-none">{{ $porteurs->getCollection()->filter(fn($p) => $p->projectAssignments->where('project', null)->count() > 0)->count() }}</p>
                <p class="text-xs text-gray-500 mt-0.5">En attente</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-xl font-extrabold text-purple-700 leading-none">{{ $structures->count() }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Structures</p>
            </div>
        </div>
    </div>

    {{-- Actions + Filtres --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('comite.porteurs.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-xs text-gray-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nom, email…"
                       class="input-field text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Structure</label>
                <select name="structure_id" class="input-field text-sm">
                    <option value="">Toutes</option>
                    @foreach($structures as $s)
                    <option value="{{ $s->id }}" {{ request('structure_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->acronym ?? $s->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            @if(request()->hasAny(['search','structure_id']))
            <a href="{{ route('comite.porteurs.index') }}" class="btn-secondary text-sm">Réinitialiser</a>
            @endif
            <div class="ml-auto">
                <a href="{{ route('comite.porteurs.create') }}" class="btn-primary text-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Nouveau porteur
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-700">{{ $porteurs->total() }} porteur(s)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left py-3 px-5 text-xs text-gray-500 font-medium w-8">#</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Porteur</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Structure</th>
                        <th class="text-center py-3 px-4 text-xs text-gray-500 font-medium">Projets</th>
                        <th class="text-left py-3 px-4 text-xs text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porteurs as $i => $porteur)
                    @php
                        $nbTotal     = $porteur->projectAssignments->count();
                        $nbSubmitted = $porteur->projectAssignments->filter(fn($a) => $a->project?->status === 'submitted')->count();
                        $nbDraft     = $porteur->projectAssignments->filter(fn($a) => $a->project?->status === 'draft')->count();
                        $nbPending   = $porteur->projectAssignments->filter(fn($a) => !$a->project)->count();
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50/70 transition-colors">
                        <td class="py-3 px-5">
                            <span class="inline-flex w-6 h-6 rounded-lg items-center justify-center text-xs font-bold bg-gray-100 text-gray-500">
                                {{ $porteurs->firstItem() + $i }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($porteur->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $porteur->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $porteur->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($porteur->structure)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $porteur->structure->acronym ?? $porteur->structure->name }}
                            </span>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                @if($nbSubmitted > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700" title="Soumis">
                                    {{ $nbSubmitted }} soumis
                                </span>
                                @endif
                                @if($nbDraft > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700" title="Brouillon">
                                    {{ $nbDraft }} brouillon
                                </span>
                                @endif
                                @if($nbPending > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500" title="En attente">
                                    {{ $nbPending }} en attente
                                </span>
                                @endif
                                @if($nbTotal === 0)
                                <span class="text-gray-300 text-xs">Aucun</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('comite.porteurs.edit', $porteur) }}"
                                   class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('comite.porteurs.send-credentials', $porteur) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Envoyer accès
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('comite.porteurs.destroy', $porteur) }}"
                                      data-confirm="Supprimer {{ $porteur->name }} et tous ses projets ?"
                                      data-confirm-title="Supprimer le porteur"
                                      data-confirm-type="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs px-2.5 py-1.5 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 font-medium transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                            <p class="text-sm">Aucun porteur enregistré.</p>
                            @if(request()->hasAny(['search','structure_id']))
                            <a href="{{ route('comite.porteurs.index') }}" class="mt-1 inline-block text-xs text-blue-600 hover:underline">Réinitialiser les filtres</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($porteurs->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">{{ $porteurs->links() }}</div>
        @endif
    </div>

</div>
@endsection
